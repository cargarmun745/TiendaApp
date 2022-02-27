$('#modalDelete').on('shown.bs.modal', function (event) {
    let deleteItem = document.getElementById('deleteItem');
    let nombreItem = document.getElementById('nombreItem');
    let element = event.relatedTarget;
    let action = element.getAttribute('data-url');
    let name = element.dataset.name;
    deleteItem.innerHTML = name;
    let tabla = element.dataset.table;
    console.log(tabla);
    nombreItem.innerHTML = tabla;
    let form = document.getElementById('modalDeleteResourceForm');
    form.action = action;
})