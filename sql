CREATE TABLE
    clientes (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        telefone VARCHAR(20) NOT NULL,
        endereco VARCHAR(255) NOT NULL,
        cpf VARCHAR(11) NOT NULL UNIQUE,
        excluido TINYINT(1) NOT NULL DEFAULT 0
    );

CREATE TABLE
    funcionarios (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        senha VARCHAR(255) NOT NULL,
        telefone VARCHAR(20) NOT NULL,
        cpf VARCHAR(11) NOT NULL UNIQUE,
        is_admin TINYINT(1) NOT NULL DEFAULT 0,
        excluido TINYINT(1) NOT NULL DEFAULT 0
    );

CREATE TABLE
    categorias (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(50) NOT NULL
    );


CREATE TABLE
    fornecedores (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        telefone VARCHAR(20) NOT NULL,
        endereco VARCHAR(255) NOT NULL
        excluido TINYINT(1) NOT NULL DEFAULT 0,
    );

    CREATE TABLE
    produtos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(255) NOT NULL,
        descricao VARCHAR(255) NOT NULL,
        id_categoria INT NOT NULL,
        tamanho ENUM('PP', 'P', 'M', 'G', 'GG') NOT NULL,
        cor VARCHAR(50) NOT NULL,
        material VARCHAR(50) NOT NULL,
        preco DECIMAL(10, 2) NOT NULL,
        quantidade_estoque INT NOT NULL,
        id_forncedor INT NOT NULL,
        excluido TINYINT(1) NOT NULL DEFAULT 0,
        FOREIGN KEY (id_categoria) REFERENCES categorias(id),
        FOREIGN KEY (id_fornecedor) REFERENCES fornecedores(id)
    );


CREATE TABLE
    alugueis (
        id INT PRIMARY KEY AUTO_INCREMENT,
        id_cliente INT NOT NULL,
        id_produto INT NOT NULL,
        data_aluguel DATE NOT NULL,
        data_devolucao DATE NOT NULL,
        valor_total DECIMAL(10, 2) NOT NULL,
        devolvido TINYINT(1) NOT NULL DEFAULT 0,
        FOREIGN KEY (id_cliente) REFERENCES clientes(id),
        FOREIGN KEY (id_produto) REFERENCES produtos(id)
    );

-- Populando a tabela de categorias

INSERT INTO categorias (nome) VALUES ('Ternos'), ('Vestidos');

-- Populando a tabela de clientes

INSERT INTO
    clientes (
        nome,
        email,
        telefone,
        endereco,
        cpf
    )
VALUES (
        'João Silva',
        'joao.silva@gmail.com',
        '11999999999',
        'Rua A, 123',
        '11111111111'
    ), (
        'Maria Souza',
        'maria.souza@gmail.com',
        '11988888888',
        'Rua B, 456',
        '22222222222'
    ), (
        'Pedro Santos',
        'pedro.santos@gmail.com',
        '11977777777',
        'Rua C, 789',
        '33333333333'
    );

-- Populando a tabela de produtos

INSERT INTO
    produtos (
        nome,
        descricao,
        id_categoria,
        tamanho,
        cor,
        material,
        preco,
        quantidade_estoque
    )
VALUES (
        'Terno preto',
        'Terno social preto com colete',
        '1',
        'M',
        'preto',
        'lã',
        200.00,
        10
    ), (
        'Terno cinza',
        'Terno social cinza com colete',
        '1',
        'G',
        'cinza',
        'lã',
        220.00,
        5
    ), (
        'Vestido azul',
        'Vestido longo azul',
        '2',
        'M',
        'azul',
        'seda',
        150.00,
        8
    ), (
        'Vestido vermelho',
        'Vestido curto vermelho',
        '2',
        'P',
        'vermelho',
        'algodão',
        100.00,
        12
    );

-- Populando a tabela de alugueis

INSERT INTO
    alugueis (
        id_cliente,
        id_produto,
        data_aluguel,
        data_devolucao,
        valor_total
    )
VALUES (
        1,
        1,
        '2023-05-01',
        '2023-05-08',
        100.00
    ), (
        2,
        3,
        '2023-05-03',
        '2023-05-10',
        80.00
    ), (
        3,
        2,
        '2023-05-05',
        '2023-05-12',
        110.00
    );

-- Populando a tabela de funcionarios

INSERT INTO
    funcionarios (nome, email, senha, is_admin)
VALUES (
        'Lucas Souza',
        'lucas.souza@gmail.com',
        '$2y$10$xSeJkxBZO2kSZKNespJJdume9titF11ZaBlD4fMihWLMXaU03bBai',
        1
    ), (
        'Ana Santos',
        'ana.santos@gmail.com',
        '$2y$10$xSeJkxBZO2kSZKNespJJdume9titF11ZaBlD4fMihWLMXaU03bBai',
        0
    ), (
        'Paulo Silva',
        'paulo.silva@gmail.com',
        '$2y$10$xSeJkxBZO2kSZKNespJJdume9titF11ZaBlD4fMihWLMXaU03bBai',
        0
    );

-- Populando a tabela de fornecedores

INSERT INTO
    fornecedores (nome, email, telefone, endereco)
VALUES (
        'Fornecedor 1',
        'fornecedor1@gmail.com',
        '11999999999',
        'Rua X, 123'
    ), (
        'Fornecedor 2',
        'fornecedor2@gmail.com',
        '11988888888',
        'Rua Y, 456'
    );