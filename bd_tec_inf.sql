
CREATE DATABASE `bd_tec_inf`;


CREATE TABLE `tb_capitulo` (
  `cdCapitulo` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `tb_figura` (
  `cdFigura` int(11) NOT NULL,
  `legenda` int(11) NOT NULL,
  `localArquivo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `tb_figura_publicacao` (
  `cdFigura` int(11) NOT NULL,
  `cdCapitulo` int(11) NOT NULL,
  `cdSecao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE `tb_publicacao` (
  `cdCapitulo` int(11) NOT NULL,
  `cdSecao` int(11) NOT NULL,
  `texto` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `tb_secao` (
  `cdSecao` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cdCapitulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tb_capitulo`
  ADD PRIMARY KEY (`cdCapitulo`);


ALTER TABLE `tb_figura`
  ADD PRIMARY KEY (`cdFigura`);


ALTER TABLE `tb_figura_publicacao`
  ADD PRIMARY KEY (`cdFigura`,`cdCapitulo`,`cdSecao`),
  ADD KEY `cdCapitulo` (`cdCapitulo`),
  ADD KEY `cdSecao` (`cdSecao`);


ALTER TABLE `tb_publicacao`
  ADD PRIMARY KEY (`cdCapitulo`,`cdSecao`),
  ADD KEY `fk_cdPublicacao_secao` (`cdSecao`);


ALTER TABLE `tb_secao`
  ADD PRIMARY KEY (`cdSecao`),
  ADD KEY `fk_cdCapitulo` (`cdCapitulo`);


ALTER TABLE `tb_figura`
  MODIFY `cdFigura` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `tb_figura_publicacao`
  ADD CONSTRAINT `tb_figura_publicacao_ibfk_1` FOREIGN KEY (`cdFigura`) REFERENCES `tb_figura` (`cdFigura`),
  ADD CONSTRAINT `tb_figura_publicacao_ibfk_2` FOREIGN KEY (`cdCapitulo`) REFERENCES `tb_publicacao` (`cdCapitulo`),
  ADD CONSTRAINT `tb_figura_publicacao_ibfk_3` FOREIGN KEY (`cdSecao`) REFERENCES `tb_publicacao` (`cdSecao`);


ALTER TABLE `tb_publicacao`
  ADD CONSTRAINT `fk_cdPublicacao_capitulo` FOREIGN KEY (`cdCapitulo`) REFERENCES `tb_capitulo` (`cdCapitulo`),
  ADD CONSTRAINT `fk_cdPublicacao_secao` FOREIGN KEY (`cdSecao`) REFERENCES `tb_secao` (`cdSecao`);


ALTER TABLE `tb_secao`
  ADD CONSTRAINT `fk_cdCapitulo` FOREIGN KEY (`cdCapitulo`) REFERENCES `tb_capitulo` (`cdCapitulo`);

