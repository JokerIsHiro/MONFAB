const data = [
    { fullname: "Juan", desc: "Hola buenas tardes", serial: "368347863", status: "on", prio: "High" },
    { fullname: "Juan", desc: "Hola buenas tardes", serial: "368347863", status: "on", prio: "High" },
    { fullname: "Miguel", desc: "Hola buenas tardes", serial: "368347863", status: "on", prio: "High" },
    { fullname: "Jose", desc: "Adios buenas tardes", serial: "368347863", status: "off", prio: "Medium" },
    { fullname: "Victor", desc: "Hola buenas tardes", serial: "59749663856", status: "on", prio: "High" },
    { fullname: "Alejandro", desc: "Hola buenas tardes", serial: "574697450", status: "off", prio: "Low" }
];

const tbody = document.querySelector('#body');
const input = document.querySelector('#filter');
const dialog = document.querySelector('#editDialog');
const form = document.querySelector('#form');

populateTable();
input.onkeyup = function (event) {
    const val = input.value.toLowerCase();
    if (val.length >= 3) {
        const rows = Array.from(tbody.querySelectorAll('tr'));
        rows.filter(function (row) {
            const fullname = row.cells[1].textContent.toLowerCase();
            const desc = row.cells[2].textContent.toLowerCase();
            if (fullname.includes(val) || desc.includes(val)) {
                row.style.color = "green";
            }
        });
    } else {
        tbody.querySelectorAll('tr').forEach(function (row) {
            row.style.color = "";
        });
    }
}

function populateTable() {
    data.forEach(item => {
        const rows = document.createElement('tr');
        const btnRemove = document.createElement('button');
        const btnEdit = document.createElement('button');
        const action = document.createElement('td');
        btnRemove.value = 'borrar';
        btnRemove.id = 'btnRemove';
        btnRemove.innerText = 'X';
        btnEdit.value = 'editar';
        btnEdit.id = 'btnEdit';
        btnEdit.innerText = 'Editar';
        action.appendChild(btnRemove);
        action.appendChild(btnEdit);
        Object.values(item).forEach(value => {
            const td = document.createElement('td');
            td.textContent = value;
            rows.insertAdjacentElement("afterbegin", action);
            rows.appendChild(td);
        });
        btnRemove.onclick = () => rows.remove();
        btnEdit.onclick = () => editRowData(item, rows);
        tbody.appendChild(rows);
    });
}

function editRowData(item, row) {

    document.querySelector('#fullname').placeholder = item.fullname;
    document.querySelector('#desc').placeholder = item.desc;
    document.querySelector('#serial').placeholder = item.serial;
    document.querySelector('#status').checked = item.status === 'on';
    document.querySelector('#prioHigh').checked = item.prio === 'High';
    document.querySelector('#prioMedium').checked= item.prio === 'Medium';
    document.querySelector('#prioLow').checked = item.prio === 'Low';

    dialog.showModal();

    form.onsubmit = (event) => {
        event.preventDefault(); //Evita que se refresque la pagina al presionar "Save"
        
        item.fullname = document.querySelector('input[name="fullname"]').value;
        item.desc = document.querySelector('input[name="desc"]').value;
        item.serial = document.querySelector('input[name="serial"]').value;
        item.status = document.querySelector('input[name="status"]').checked ? "on" : "off";
        item.prio = document.querySelector('input[name="prio"]:checked').value;

        const cells = row.querySelectorAll('td');

        cells[1].textContent = item.fullname;
        cells[2].textContent = item.desc;
        cells[3].textContent = item.serial;
        cells[4].textContent = item.status;
        cells[5].textContent = item.prio;

        dialog.close();
    }
}
