let clientes = JSON.parse(localStorage.getItem("clientes")) || [];
let veiculos = JSON.parse(localStorage.getItem("veiculos")) || [];
let reservas = JSON.parse(localStorage.getItem("reservas")) || [];
let locacoes = JSON.parse(localStorage.getItem("locacoes")) || [];

function mostrarSecao(id) {
    let secoes = document.querySelectorAll(".secao");

    secoes.forEach(secao => {
        secao.classList.remove("ativa");
    });

    document.getElementById(id).classList.add("ativa");

    atualizarSelects();
}

function gerarId(lista) {
    if (lista.length === 0) {
        return 1;
    }

    return lista[lista.length - 1].id + 1;
}

function salvarDados() {
    localStorage.setItem("clientes", JSON.stringify(clientes));
    localStorage.setItem("veiculos", JSON.stringify(veiculos));
    localStorage.setItem("reservas", JSON.stringify(reservas));
    localStorage.setItem("locacoes", JSON.stringify(locacoes));
}

/* CRUD CLIENTE */

function salvarCliente(event) {
    event.preventDefault();

    let id = document.getElementById("clienteId").value;
    let nome = document.getElementById("clienteNome").value;
    let cpf = document.getElementById("clienteCpf").value;
    let telefone = document.getElementById("clienteTelefone").value;
    let email = document.getElementById("clienteEmail").value;

    if (id === "") {
        clientes.push({
            id: gerarId(clientes),
            nome: nome,
            cpf: cpf,
            telefone: telefone,
            email: email
        });
    } else {
        let cliente = clientes.find(c => c.id == id);

        cliente.nome = nome;
        cliente.cpf = cpf;
        cliente.telefone = telefone;
        cliente.email = email;
    }

    document.querySelector("#clientes form").reset();
    document.getElementById("clienteId").value = "";

    salvarDados();
    listarClientes();
    atualizarSelects();
}

function listarClientes() {
    let lista = document.getElementById("listaClientes");
    lista.innerHTML = "";

    clientes.forEach(cliente => {
        lista.innerHTML += `
            <tr>
                <td>${cliente.nome}</td>
                <td>${cliente.cpf}</td>
                <td>${cliente.telefone}</td>
                <td>${cliente.email}</td>
                <td>
                    <button class="btn-editar" onclick="editarCliente(${cliente.id})">Editar</button>
                    <button class="btn-excluir" onclick="excluirCliente(${cliente.id})">Excluir</button>
                </td>
            </tr>
        `;
    });
}

function editarCliente(id) {
    let cliente = clientes.find(c => c.id === id);

    document.getElementById("clienteId").value = cliente.id;
    document.getElementById("clienteNome").value = cliente.nome;
    document.getElementById("clienteCpf").value = cliente.cpf;
    document.getElementById("clienteTelefone").value = cliente.telefone;
    document.getElementById("clienteEmail").value = cliente.email;
}

function excluirCliente(id) {
    if (confirm("Deseja excluir este cliente?")) {
        clientes = clientes.filter(c => c.id !== id);

        salvarDados();
        listarClientes();
        atualizarSelects();
    }
}

/* CRUD VEÍCULO */

function salvarVeiculo(event) {
    event.preventDefault();

    let id = document.getElementById("veiculoId").value;
    let placa = document.getElementById("veiculoPlaca").value;
    let modelo = document.getElementById("veiculoModelo").value;
    let marca = document.getElementById("veiculoMarca").value;
    let ano = document.getElementById("veiculoAno").value;
    let categoria = document.getElementById("veiculoCategoria").value;
    let status = document.getElementById("veiculoStatus").value;

    if (id === "") {
        veiculos.push({
            id: gerarId(veiculos),
            placa: placa,
            modelo: modelo,
            marca: marca,
            ano: ano,
            categoria: categoria,
            status: status
        });
    } else {
        let veiculo = veiculos.find(v => v.id == id);

        veiculo.placa = placa;
        veiculo.modelo = modelo;
        veiculo.marca = marca;
        veiculo.ano = ano;
        veiculo.categoria = categoria;
        veiculo.status = status;
    }

    document.querySelector("#veiculos form").reset();
    document.getElementById("veiculoId").value = "";

    salvarDados();
    listarVeiculos();
    atualizarSelects();
}

function listarVeiculos() {
    let lista = document.getElementById("listaVeiculos");
    lista.innerHTML = "";

    veiculos.forEach(veiculo => {
        lista.innerHTML += `
            <tr>
                <td>${veiculo.placa}</td>
                <td>${veiculo.modelo}</td>
                <td>${veiculo.marca}</td>
                <td>${veiculo.ano}</td>
                <td>${veiculo.categoria}</td>
                <td>${veiculo.status}</td>
                <td>
                    <button class="btn-editar" onclick="editarVeiculo(${veiculo.id})">Editar</button>
                    <button class="btn-excluir" onclick="excluirVeiculo(${veiculo.id})">Excluir</button>
                </td>
            </tr>
        `;
    });
}

function editarVeiculo(id) {
    let veiculo = veiculos.find(v => v.id === id);

    document.getElementById("veiculoId").value = veiculo.id;
    document.getElementById("veiculoPlaca").value = veiculo.placa;
    document.getElementById("veiculoModelo").value = veiculo.modelo;
    document.getElementById("veiculoMarca").value = veiculo.marca;
    document.getElementById("veiculoAno").value = veiculo.ano;
    document.getElementById("veiculoCategoria").value = veiculo.categoria;
    document.getElementById("veiculoStatus").value = veiculo.status;
}

function excluirVeiculo(id) {
    if (confirm("Deseja excluir este veículo?")) {
        veiculos = veiculos.filter(v => v.id !== id);

        salvarDados();
        listarVeiculos();
        atualizarSelects();
    }
}

/* CRUD RESERVA */

function salvarReserva(event) {
    event.preventDefault();

    let id = document.getElementById("reservaId").value;
    let clienteId = document.getElementById("reservaCliente").value;
    let veiculoId = document.getElementById("reservaVeiculo").value;
    let dataReserva = document.getElementById("reservaData").value;
    let retirada = document.getElementById("reservaRetirada").value;
    let devolucao = document.getElementById("reservaDevolucao").value;

    if (id === "") {
        reservas.push({
            id: gerarId(reservas),
            clienteId: Number(clienteId),
            veiculoId: Number(veiculoId),
            dataReserva: dataReserva,
            retirada: retirada,
            devolucao: devolucao
        });
    } else {
        let reserva = reservas.find(r => r.id == id);

        reserva.clienteId = Number(clienteId);
        reserva.veiculoId = Number(veiculoId);
        reserva.dataReserva = dataReserva;
        reserva.retirada = retirada;
        reserva.devolucao = devolucao;
    }

    document.querySelector("#reservas form").reset();
    document.getElementById("reservaId").value = "";

    salvarDados();
    listarReservas();
}

function listarReservas() {
    let lista = document.getElementById("listaReservas");
    lista.innerHTML = "";

    reservas.forEach(reserva => {
        let cliente = clientes.find(c => c.id === reserva.clienteId);
        let veiculo = veiculos.find(v => v.id === reserva.veiculoId);

        lista.innerHTML += `
            <tr>
                <td>${cliente ? cliente.nome : "Cliente removido"}</td>
                <td>${veiculo ? veiculo.modelo + " - " + veiculo.placa : "Veículo removido"}</td>
                <td>${reserva.dataReserva}</td>
                <td>${reserva.retirada}</td>
                <td>${reserva.devolucao}</td>
                <td>
                    <button class="btn-editar" onclick="editarReserva(${reserva.id})">Editar</button>
                    <button class="btn-excluir" onclick="excluirReserva(${reserva.id})">Excluir</button>
                </td>
            </tr>
        `;
    });
}

function editarReserva(id) {
    let reserva = reservas.find(r => r.id === id);

    document.getElementById("reservaId").value = reserva.id;
    document.getElementById("reservaCliente").value = reserva.clienteId;
    document.getElementById("reservaVeiculo").value = reserva.veiculoId;
    document.getElementById("reservaData").value = reserva.dataReserva;
    document.getElementById("reservaRetirada").value = reserva.retirada;
    document.getElementById("reservaDevolucao").value = reserva.devolucao;
}

function excluirReserva(id) {
    if (confirm("Deseja excluir esta reserva?")) {
        reservas = reservas.filter(r => r.id !== id);

        salvarDados();
        listarReservas();
    }
}

/* CRUD LOCAÇÃO */

function salvarLocacao(event) {
    event.preventDefault();

    let id = document.getElementById("locacaoId").value;
    let clienteId = document.getElementById("locacaoCliente").value;
    let veiculoId = document.getElementById("locacaoVeiculo").value;
    let inicio = document.getElementById("locacaoInicio").value;
    let fim = document.getElementById("locacaoFim").value;
    let valor = document.getElementById("locacaoValor").value;

    if (id === "") {
        locacoes.push({
            id: gerarId(locacoes),
            clienteId: Number(clienteId),
            veiculoId: Number(veiculoId),
            inicio: inicio,
            fim: fim,
            valor: valor
        });
    } else {
        let locacao = locacoes.find(l => l.id == id);

        locacao.clienteId = Number(clienteId);
        locacao.veiculoId = Number(veiculoId);
        locacao.inicio = inicio;
        locacao.fim = fim;
        locacao.valor = valor;
    }

    document.querySelector("#locacoes form").reset();
    document.getElementById("locacaoId").value = "";

    salvarDados();
    listarLocacoes();
}

function listarLocacoes() {
    let lista = document.getElementById("listaLocacoes");
    lista.innerHTML = "";

    locacoes.forEach(locacao => {
        let cliente = clientes.find(c => c.id === locacao.clienteId);
        let veiculo = veiculos.find(v => v.id === locacao.veiculoId);

        lista.innerHTML += `
            <tr>
                <td>${cliente ? cliente.nome : "Cliente removido"}</td>
                <td>${veiculo ? veiculo.modelo + " - " + veiculo.placa : "Veículo removido"}</td>
                <td>${locacao.inicio}</td>
                <td>${locacao.fim}</td>
                <td>R$ ${Number(locacao.valor).toFixed(2)}</td>
                <td>
                    <button class="btn-editar" onclick="editarLocacao(${locacao.id})">Editar</button>
                    <button class="btn-excluir" onclick="excluirLocacao(${locacao.id})">Excluir</button>
                </td>
            </tr>
        `;
    });
}

function editarLocacao(id) {
    let locacao = locacoes.find(l => l.id === id);

    document.getElementById("locacaoId").value = locacao.id;
    document.getElementById("locacaoCliente").value = locacao.clienteId;
    document.getElementById("locacaoVeiculo").value = locacao.veiculoId;
    document.getElementById("locacaoInicio").value = locacao.inicio;
    document.getElementById("locacaoFim").value = locacao.fim;
    document.getElementById("locacaoValor").value = locacao.valor;
}

function excluirLocacao(id) {
    if (confirm("Deseja excluir esta locação?")) {
        locacoes = locacoes.filter(l => l.id !== id);

        salvarDados();
        listarLocacoes();
    }
}

/* SELECTS */

function atualizarSelects() {
    let reservaCliente = document.getElementById("reservaCliente");
    let reservaVeiculo = document.getElementById("reservaVeiculo");
    let locacaoCliente = document.getElementById("locacaoCliente");
    let locacaoVeiculo = document.getElementById("locacaoVeiculo");

    reservaCliente.innerHTML = '<option value="">Selecione um cliente</option>';
    locacaoCliente.innerHTML = '<option value="">Selecione um cliente</option>';

    clientes.forEach(cliente => {
        reservaCliente.innerHTML += `<option value="${cliente.id}">${cliente.nome}</option>`;
        locacaoCliente.innerHTML += `<option value="${cliente.id}">${cliente.nome}</option>`;
    });

    reservaVeiculo.innerHTML = '<option value="">Selecione um veículo</option>';
    locacaoVeiculo.innerHTML = '<option value="">Selecione um veículo</option>';

    veiculos.forEach(veiculo => {
        reservaVeiculo.innerHTML += `<option value="${veiculo.id}">${veiculo.modelo} - ${veiculo.placa}</option>`;
        locacaoVeiculo.innerHTML += `<option value="${veiculo.id}">${veiculo.modelo} - ${veiculo.placa}</option>`;
    });
}

/* CARREGAMENTO INICIAL */

listarClientes();
listarVeiculos();
listarReservas();
listarLocacoes();
atualizarSelects();