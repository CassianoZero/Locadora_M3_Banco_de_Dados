-- CRIAÇÃO DE ESQUEMA E TABELAS
CREATE SCHEMA locadora_carros;
use locadora_carros;
-- DROP SCHEMA locadora_carros;

-- DROP TABLE Cliente;
CREATE TABLE cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    email VARCHAR(100)
);

CREATE TABLE telefone_cliente (
    id_telefone INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(20) NOT NULL,
    tipo VARCHAR(20),
    id_cliente INT NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
);

CREATE TABLE endereco_cliente (
    id_endereco INT AUTO_INCREMENT PRIMARY KEY,
    cep VARCHAR(10),
    logradouro VARCHAR(100),
    numero VARCHAR(10),
    bairro VARCHAR(60),
    cidade VARCHAR(60),
    estado CHAR(2),
    id_cliente INT NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
);

CREATE TABLE marca (
    id_marca INT AUTO_INCREMENT PRIMARY KEY,
    nome_marca VARCHAR(50) NOT NULL
);

CREATE TABLE modelo (
    id_modelo INT AUTO_INCREMENT PRIMARY KEY,
    nome_modelo VARCHAR(50) NOT NULL,
    id_marca INT NOT NULL,
    FOREIGN KEY (id_marca) REFERENCES marca(id_marca)
);

CREATE TABLE categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nome_categoria VARCHAR(30) NOT NULL
);

CREATE TABLE status_veiculo (
    id_status INT AUTO_INCREMENT PRIMARY KEY,
    descricao_status VARCHAR(30) NOT NULL
);

CREATE TABLE veiculo (
    id_veiculo INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(10) NOT NULL UNIQUE,
    ano INT,
    id_modelo INT NOT NULL,
    id_categoria INT NOT NULL,
    id_status INT NOT NULL,
    FOREIGN KEY (id_modelo) REFERENCES modelo(id_modelo),
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria),
    FOREIGN KEY (id_status) REFERENCES status_veiculo(id_status)
);

CREATE TABLE reserva (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    data_reserva DATE NOT NULL,
    data_retirada DATE NOT NULL,
    data_devolucao DATE NOT NULL,
    id_cliente INT NOT NULL,
    id_veiculo INT NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_veiculo) REFERENCES veiculo(id_veiculo)
);

CREATE TABLE locacao (
    id_locacao INT AUTO_INCREMENT PRIMARY KEY,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    valor_total DECIMAL(10,2),
    id_cliente INT NOT NULL,
    id_veiculo INT NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_veiculo) REFERENCES veiculo(id_veiculo)
);

INSERT INTO marca (nome_marca) VALUES
('Chevrolet'),
('Hyundai'),
('Fiat'),
('Volkswagen');

INSERT INTO modelo (nome_modelo, id_marca) VALUES
('Onix', 1),
('HB20', 2),
('Argo', 3),
('Polo', 4);

INSERT INTO categoria (nome_categoria) VALUES
('Hatch'),
('Sedan'),
('SUV');

INSERT INTO status_veiculo (descricao_status) VALUES
('Disponível'),
('Reservado'),
('Alugado'),
('Manutenção');

INSERT INTO cliente (nome, cpf, email) VALUES
('João Silva', '111.111.111-11', 'joao@email.com'),
('Maria Souza', '222.222.222-22', 'maria@email.com');

INSERT INTO telefone_cliente (numero, tipo, id_cliente) VALUES
('47999999999', 'Celular', 1),
('47988888888', 'Celular', 2);

INSERT INTO endereco_cliente (cep, logradouro, numero, bairro, cidade, estado, id_cliente) VALUES
('88300-000', 'Rua Central', '100', 'Centro', 'Itajaí', 'SC', 1),
('88300-001', 'Rua das Flores', '200', 'Fazenda', 'Itajaí', 'SC', 2);

INSERT INTO veiculo (placa, ano, id_modelo, id_categoria, id_status) VALUES
('ABC1A23', 2024, 1, 1, 1),
('XYZ9B45', 2023, 2, 1, 1);

INSERT INTO reserva (data_reserva, data_retirada, data_devolucao, id_cliente, id_veiculo) VALUES
('2026-06-01', '2026-06-05', '2026-06-10', 1, 1);

INSERT INTO locacao (data_inicio, data_fim, valor_total, id_cliente, id_veiculo) VALUES
('2026-06-05', '2026-06-10', 850.00, 1, 1);

select * from cliente;
