-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 13-Jun-2019 às 20:30
-- Versão do servidor: 10.1.37-MariaDB
-- versão do PHP: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bd_tec_inf`
--
CREATE DATABASE IF NOT EXISTS `bd_tec_inf` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `bd_tec_inf`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_capitulo`
--

CREATE TABLE `tb_capitulo` (
  `cdCapitulo` int(11) NOT NULL,
  `nome` varchar(100) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_figura`
--

CREATE TABLE `tb_figura` (
  `cdFigura` int(11) NOT NULL,
  `legenda` text COLLATE latin1_general_ci NOT NULL,
  `caminho` varchar(200) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_figura_publicacao`
--

CREATE TABLE `tb_figura_publicacao` (
  `cdFigura` int(11) NOT NULL,
  `cdPublicacao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_publicacao`
--

CREATE TABLE `tb_publicacao` (
  `cdPublicacao` int(11) NOT NULL,
  `cdSecao` int(11) NOT NULL,
  `cdCapitulo` int(11) NOT NULL,
  `texto` longtext COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_secao`
--

CREATE TABLE `tb_secao` (
  `cdSecao` int(11) NOT NULL,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `cdCapitulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_capitulo`
--
ALTER TABLE `tb_capitulo`
  ADD PRIMARY KEY (`cdCapitulo`);

--
-- Indexes for table `tb_figura`
--
ALTER TABLE `tb_figura`
  ADD PRIMARY KEY (`cdFigura`);

--
-- Indexes for table `tb_figura_publicacao`
--
ALTER TABLE `tb_figura_publicacao`
  ADD PRIMARY KEY (`cdFigura`,`cdPublicacao`),
  ADD KEY `cdPublicacao` (`cdPublicacao`);

--
-- Indexes for table `tb_publicacao`
--
ALTER TABLE `tb_publicacao`
  ADD PRIMARY KEY (`cdPublicacao`),
  ADD KEY `cdCapitulo` (`cdCapitulo`),
  ADD KEY `cdSecao` (`cdSecao`);

--
-- Indexes for table `tb_secao`
--
ALTER TABLE `tb_secao`
  ADD PRIMARY KEY (`cdSecao`,`cdCapitulo`),
  ADD KEY `cdCapitulo` (`cdCapitulo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_figura`
--
ALTER TABLE `tb_figura`
  MODIFY `cdFigura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_publicacao`
--
ALTER TABLE `tb_publicacao`
  MODIFY `cdPublicacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `tb_figura_publicacao`
--
ALTER TABLE `tb_figura_publicacao`
  ADD CONSTRAINT `tb_figura_publicacao_ibfk_1` FOREIGN KEY (`cdFigura`) REFERENCES `tb_figura` (`cdFigura`),
  ADD CONSTRAINT `tb_figura_publicacao_ibfk_2` FOREIGN KEY (`cdPublicacao`) REFERENCES `tb_publicacao` (`cdPublicacao`);

--
-- Limitadores para a tabela `tb_publicacao`
--
ALTER TABLE `tb_publicacao`
  ADD CONSTRAINT `tb_publicacao_ibfk_1` FOREIGN KEY (`cdCapitulo`) REFERENCES `tb_capitulo` (`cdCapitulo`),
  ADD CONSTRAINT `tb_publicacao_ibfk_2` FOREIGN KEY (`cdSecao`) REFERENCES `tb_secao` (`cdSecao`);

--
-- Limitadores para a tabela `tb_secao`
--
ALTER TABLE `tb_secao`
  ADD CONSTRAINT `tb_secao_ibfk_1` FOREIGN KEY (`cdCapitulo`) REFERENCES `tb_capitulo` (`cdCapitulo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
