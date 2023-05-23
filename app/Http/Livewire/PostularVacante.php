<?php

namespace App\Http\Livewire;

use App\Models\Vacante;
use App\Notifications\NuevoCandidato;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostularVacante extends Component
{
  public $cv;
  public $vacante;


  protected $rules = [
    'cv' => 'required|mimes:pdf'
  ];

  use WithFileUploads;

  public function mount(Vacante $vacante){
    $this->vacante = $vacante;
  }

  public function postularme(){
    $this->validate();

    //Almacenar el CV en disco duro
    $cv = $this->cv->store('public/cv');
    $datos['cv'] = str_replace('public/cv/', '', $cv);
    //Crear una vacante
    $this->vacante->candidatos()->create([
      'user_id' => auth()->user()->id,
      'cv' => $datos['cv']
    ]);


    //Crear notificación y enviar el email
    $this->vacante->reclutador->notify(new NuevoCandidato($this->vacante->id, $this->vacante->titulo, auth()->user()->id));
    
    //Redireccionar al usuario
    session()->flash('mensaje', 'Tu CV ha sido envíado correctamente');
    return redirect()->back();

    //mostrar al usuario un mensaje de ok

  }

  public function render()
  {
    return view('livewire.postular-vacante');
  }
}
