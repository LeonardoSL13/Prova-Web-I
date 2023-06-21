CREATE TABLE Marca ( id INT PRIMARY KEY, nome VARCHAR(100) );

CREATE TABLE Cores ( id INT PRIMARY KEY, nome VARCHAR(50) );

CREATE TABLE
    Sapato (
        id INT PRIMARY KEY,
        nome VARCHAR(100),
        marca_id INT,
        cor_id INT,
        FOREIGN KEY (marca_id) REFERENCES Marca(id),
        FOREIGN KEY (cor_id) REFERENCES Cores(id)
    );

INSERT INTO Marca (id, nome)
VALUES (1, 'Nike'), (2, 'Adidas'), (3, 'Puma');

INSERT INTO Cores (id, nome)
VALUES (1, 'Preto'), (2, 'Branco'), (3, 'Azul');

INSERT INTO
    Sapato (id, nome, marca_id, cor_id)
VALUES (1, 'Air Max', 1, 1), (2, 'Superstar', 2, 2), (3, 'Suede Classic', 3, 3);

SELECT
    Sapato.nome AS nome_sapato,
    Marca.nome AS nome_marca,
    Cores.nome AS nome_cor
FROM Sapato
    INNER JOIN Marca ON Sapato.marca_id = Marca.id
    INNER JOIN Cores ON Sapato.cor_id = Cores.id;