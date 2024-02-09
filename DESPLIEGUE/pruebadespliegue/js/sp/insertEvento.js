  //https://www.npmjs.com/package/exif-js Libreria para controlar el tamaño de foto
  const urlFormularioNuevoEvento = 'http://localhost:3000/backend/api_racebook/eventos/new/';
  const urlArchivosFormularioNuevoEvento = 'http://localhost:3000/backend/api_racebook/eventos/new_archivos/';
  const formFormularioNuevoEvento = document.getElementById('formularioRegistroEventos');
  const imagenFormularioNuevoEvento = document.getElementById('imagenFormularioNuevoEvento');
  const formAlertFormularioNuevoEvento = document.getElementById('formAlertFormularioNuevoEvento');
  const nombreFormularioNuevoEvento = document.getElementById('nombreFormularioNuevoEvento'); 
  const fechaInicioFormularioNuevoEvento = document.getElementById('fechaInicioFormularioNuevoEvento');
  const fechaFinFormularioNuevoEvento = document.getElementById('fechaFinFormularioNuevoEvento'); 
  const localidadFormularioNuevoEvento = document.getElementById('localidadFormularioNuevoEvento'); 
  const provinciaFormularioNuevoEvento = document.getElementById('provinciaFormularioNuevoEvento');    
  const webEventoFormularioNuevoEvento = document.getElementById('webEventoFormularioNuevoEvento'); 
  const cartelFormularioNuevoEvento = document.getElementById('cartelFormularioNuevoEvento');
  const registrarEventoFormularioNuevoEvento = document.getElementById('registrarEventoFormularioNuevoEvento');
  const reglamentoFormularioNuevoEvento = document.getElementById('reglamentoFormularioNuevoEvento');
  
  let contadorImagenesFormularioNuevoEvento = 0;

  
  let eventoId;

  registrarEvento.addEventListener('click', (e) => {
    e.preventDefault();
    if(nombreFormularioNuevoEvento.value == '' || fechaInicioFormularioNuevoEvento.value == '' || fechaFinFormularioNuevoEvento.value == '' || localidadFormularioNuevoEvento.value == '' || provinciaFormularioNuevoEvento.value == '' || webEventoFormularioNuevoEvento.value == '' || reglamentoFormularioNuevoEvento.value == '' || cartelFormularioNuevoEvento.value == ''){
      formAlert.innerHTML = 'Rellena todos los campos';
    }else{
      let formDataFormularioNuevoEvento = new FormData(form);

      fetch(urlArchivos, {
        headers: {
          'Authorization': `Bearer ${getLocalJWT()}`
        },
        method: 'POST',
        body: formDataFormularioNuevoEvento,
      })
      .then(response => {
        if(response.status == 201){
            return response.json();
        }else{
          formAlertFormularioNuevoEvento.textContent = 'Ha habido un error al registrar el evento si no sabes que ocurre llama a la policía';
        }   
      })
      .then(data => {
            //guardo una variable con el id del evento
            localStorage.setItem('eventoId', data.eventoId);
            formFormularioNuevoEvento.classList.toggle('hidden');
            formFormularioNuevaCarrera.classList.toggle('hidden');
      })

    }
  });