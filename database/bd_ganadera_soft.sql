-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: ec2-52-53-127-245.us-west-1.compute.amazonaws.com    Database: ganaderasoft
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Inventario_Bufalo`
--

DROP TABLE IF EXISTS `Inventario_Bufalo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Inventario_Bufalo` (
  `id_Inv_B` int NOT NULL AUTO_INCREMENT,
  `id_Finca` int NOT NULL,
  `Num_Becerro` int DEFAULT NULL,
  `Num_Anojo` int DEFAULT NULL,
  `Num_Bubilla` int DEFAULT NULL,
  `Num_Bufalo` int DEFAULT NULL,
  `Fecha_Inventario` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_Inv_B`),
  KEY `id_Finca` (`id_Finca`),
  CONSTRAINT `Inventario_Bufalo_ibfk_1` FOREIGN KEY (`id_Finca`) REFERENCES `finca` (`id_Finca`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Inventario_Bufalo`
--

LOCK TABLES `Inventario_Bufalo` WRITE;
/*!40000 ALTER TABLE `Inventario_Bufalo` DISABLE KEYS */;
/*!40000 ALTER TABLE `Inventario_Bufalo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `afiliacion`
--

DROP TABLE IF EXISTS `afiliacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `afiliacion` (
  `id_Personal_P` bigint unsigned NOT NULL,
  `id_Personal_T` bigint unsigned NOT NULL,
  `id_Finca` int NOT NULL,
  `Estado` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `receptor_solicitud` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_Personal_P`,`id_Personal_T`),
  KEY `FK_Trancriptor` (`id_Personal_T`),
  KEY `id_Finca` (`id_Finca`),
  CONSTRAINT `afiliacion_ibfk_1` FOREIGN KEY (`id_Finca`) REFERENCES `finca` (`id_Finca`),
  CONSTRAINT `FK_Propietario` FOREIGN KEY (`id_Personal_P`) REFERENCES `propietario` (`id`),
  CONSTRAINT `FK_Trancriptor` FOREIGN KEY (`id_Personal_T`) REFERENCES `transcriptor` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `afiliacion`
--

LOCK TABLES `afiliacion` WRITE;
/*!40000 ALTER TABLE `afiliacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `afiliacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `animal`
--

DROP TABLE IF EXISTS `animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `animal` (
  `id_Animal` int NOT NULL AUTO_INCREMENT,
  `id_Rebano` int NOT NULL,
  `Nombre` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `codigo_animal` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Sexo` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `Procedencia` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `archivado` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fk_composicion_raza` int NOT NULL,
  PRIMARY KEY (`id_Animal`),
  KEY `id_Rebano` (`id_Rebano`),
  KEY `fk_posee` (`fk_composicion_raza`),
  CONSTRAINT `Animal_ibfk_2` FOREIGN KEY (`id_Rebano`) REFERENCES `rebano` (`id_Rebano`),
  CONSTRAINT `fk_posee` FOREIGN KEY (`fk_composicion_raza`) REFERENCES `composicion_raza` (`id_Composicion`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `animal`
--

LOCK TABLES `animal` WRITE;
/*!40000 ALTER TABLE `animal` DISABLE KEYS */;
INSERT INTO `animal` VALUES (13,6,'Negron','ANI-001','M','2022-03-15','Finca Procedencia 01',0,'2025-08-16 18:36:25','2025-08-16 18:36:25',70),(14,6,'Mariposa','ANI-002','F','2022-03-15','Finca Procedencia 04',0,'2025-08-16 21:44:34','2025-10-15 20:35:52',71),(15,6,'Marianita','ANI-003','F','2025-10-01','Caja Seca. Finca Urbina',0,'2025-10-13 23:02:08','2025-10-15 16:29:59',71),(16,7,'Santa','ANI-004','F','2025-10-11','Mi Rebaño',0,'2025-10-15 20:34:56','2025-10-15 20:35:11',81),(17,7,'Pintada','ANI-009','F','2025-07-01','Hacienda San Jorge',0,'2025-10-16 19:43:49','2025-10-16 19:43:49',78);
/*!40000 ALTER TABLE `animal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arbol_gen`
--

DROP TABLE IF EXISTS `arbol_gen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `arbol_gen` (
  `id_hijo` int NOT NULL,
  `id_padre` int NOT NULL,
  `tipo` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_hijo`,`id_padre`),
  KEY `fk_padre` (`id_padre`),
  CONSTRAINT `fk_hijo` FOREIGN KEY (`id_hijo`) REFERENCES `animal` (`id_Animal`),
  CONSTRAINT `fk_padre` FOREIGN KEY (`id_padre`) REFERENCES `animal` (`id_Animal`),
  CONSTRAINT `ck_tipo` CHECK ((`tipo` in (_utf8mb4'Madre',_utf8mb4'Padre')))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arbol_gen`
--

LOCK TABLES `arbol_gen` WRITE;
/*!40000 ALTER TABLE `arbol_gen` DISABLE KEYS */;
/*!40000 ALTER TABLE `arbol_gen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cambios_animal`
--

DROP TABLE IF EXISTS `cambios_animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cambios_animal` (
  `id_Cambio` int NOT NULL AUTO_INCREMENT,
  `Fecha_Cambio` date DEFAULT NULL,
  `Etapa_Cambio` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Peso` float NOT NULL,
  `Altura` float NOT NULL,
  `Comentario` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cambios_etapa_anid` int NOT NULL,
  `cambios_etapa_etid` int NOT NULL,
  PRIMARY KEY (`id_Cambio`),
  KEY `cambios_etapa_animal` (`cambios_etapa_anid`,`cambios_etapa_etid`),
  CONSTRAINT `cambios_etapa_animal` FOREIGN KEY (`cambios_etapa_anid`, `cambios_etapa_etid`) REFERENCES `etapa_animal` (`etan_animal_id`, `etan_etapa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cambios_animal`
--

LOCK TABLES `cambios_animal` WRITE;
/*!40000 ALTER TABLE `cambios_animal` DISABLE KEYS */;
INSERT INTO `cambios_animal` VALUES (1,'2025-10-12','Bucerra',100,120,'Nuevo registro','2025-10-13 23:20:42','2025-10-13 23:20:42',15,24),(2,'2025-10-14','Bucerra',100,120,'nuevo registro','2025-10-15 20:37:15','2025-10-15 20:37:15',16,24),(3,'2025-10-16','Bucerra',350,200,'Nuevo registro de Marianita','2025-10-16 19:43:52','2025-10-16 19:43:52',15,24);
/*!40000 ALTER TABLE `cambios_animal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `casa_comercial`
--

DROP TABLE IF EXISTS `casa_comercial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `casa_comercial` (
  `casa_id` int NOT NULL AUTO_INCREMENT,
  `laboratorio` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `marca_comercial` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`casa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `casa_comercial`
--

LOCK TABLES `casa_comercial` WRITE;
/*!40000 ALTER TABLE `casa_comercial` DISABLE KEYS */;
INSERT INTO `casa_comercial` VALUES (1,'Cala','Aftovac',NULL,NULL),(2,'Cala','Ravax',NULL,NULL),(3,'Cala','Leptovac',NULL,NULL),(4,'Cala','Estomavac',NULL,NULL),(5,'Vecol','Aftogan',NULL,NULL),(6,'Vecol','Rabigan',NULL,NULL),(7,'Vecol','V Estomatitis',NULL,NULL),(8,'Agropharma','Delta-PGM',NULL,NULL),(9,'LAVERLAM','Combibac R8',NULL,NULL);
/*!40000 ALTER TABLE `casa_comercial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `composicion_raza`
--

DROP TABLE IF EXISTS `composicion_raza`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `composicion_raza` (
  `id_Composicion` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Siglas` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Pelaje` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Proposito` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Tipo_Raza` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Origen` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Caracteristica_Especial` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Proporcion_Raza` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fk_id_Finca` int DEFAULT NULL,
  `fk_tipo_animal_id` int DEFAULT NULL,
  PRIMARY KEY (`id_Composicion`),
  KEY `fk_crea` (`fk_id_Finca`),
  KEY `fk_tiene_tipo` (`fk_tipo_animal_id`),
  CONSTRAINT `fk_crea` FOREIGN KEY (`fk_id_Finca`) REFERENCES `finca` (`id_Finca`),
  CONSTRAINT `fk_tiene_tipo` FOREIGN KEY (`fk_tipo_animal_id`) REFERENCES `tipo_animal` (`tipo_animal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `composicion_raza`
--

LOCK TABLES `composicion_raza` WRITE;
/*!40000 ALTER TABLE `composicion_raza` DISABLE KEYS */;
INSERT INTO `composicion_raza` VALUES (70,'Shortorn','SHO','Rojo-Blanco','Doble','Bos Taurus','Noroeste Inglaterra','Adaptabilidad','Grande',NULL,NULL,NULL,NULL),(71,'Hereford','HER','Colorado Bayo y manchas blancas en la cabeza','Carne','Bos Taurus','Suroeste Inglaterra','madurez precoz','Grande',NULL,NULL,NULL,NULL),(72,'Aberdeen Angus','ANG','Negro o Rojo','Carne','Bos Taurus','Escocia','longevidad','Grande',NULL,NULL,NULL,NULL),(73,'Charolais','CHA','Blanco Crema','Carne','Bos Taurus','Charolles -Francia','gran musculatura','Grande',NULL,NULL,NULL,NULL),(74,'Simmental','SIM','Amarillo castaño a rojo','Doble','Bos Taurus','Valle Simme - Suiza','Docil','Grande',NULL,NULL,NULL,NULL),(75,'Chianina','CHI','Blanco solido','Carne','Bos Taurus','Valle de chiana - Italia','facil manejo','Grande',NULL,NULL,NULL,NULL),(76,'Romagnola','ROM','Blanco renegrido','Carne','Bos Taurus','Italia','Musculoso','Grande',NULL,NULL,NULL,NULL),(77,'Marchigiana','MAR','blanco o gris claro','Carne','Bos Taurus','Italia','Gran tamaño','Grande',NULL,NULL,NULL,NULL),(78,'Piemontesa','PIE','Blanco grisaceo o crema','Carne','Bos Taurus','Norte de Italia','Doble musculo','Grande',NULL,NULL,NULL,NULL),(79,'Red Poll','RPO','Rojo','Doble','Bos Taurus','Inglaterra','No tiene cuernos','Grande',NULL,NULL,NULL,NULL),(80,'Romosinuano','ROM','Rojo','Doble','Bos Taurus','Colombia (Criollo)','No tiene cuernos','Mediano',NULL,NULL,NULL,NULL),(81,'Senepol','SEN','Rojo','Doble','Bos Taurus','Isla St.Croix','No tiene cuernos','Mediano',NULL,NULL,NULL,NULL),(82,'Guzerat','GUZ','Blanco o gris','Doble','Bos Indicus','India','Rusticidad','Gande',NULL,NULL,NULL,NULL),(83,'Nelore','NEL','Blanco','Doble','Bos Indicus','Madras - India','Rusticidad','Grande',NULL,NULL,NULL,NULL),(84,'GYR','GYR','Marron con manchas blancas','Doble','Bos Indicus','Gujarat - India','Rusticidad','Grande',NULL,NULL,NULL,NULL),(85,'Indubrasil','IND','Blanco o gris','Doble','Bos Indicus','Brasil','Rusticidad','Grande',NULL,NULL,NULL,NULL),(86,'Brahman','BRA','Blanco o Rojo','Doble','Bos Indicus','Texas - USA','Rusticidad','Grande',NULL,NULL,NULL,NULL),(87,'Holstein','HOL','Despigmentacion debajo del blanco','Leche','Lechera','Holanda','Alta produccion lechera','Grande',NULL,NULL,NULL,NULL),(88,'Pardo Suizo','PS','Color pardo','Leche','Bos Taurus','Suiza','Caminadora','Mediana',NULL,NULL,NULL,NULL),(89,'Carora','CAR','Blanco','Doble','Bos Taurus','Carora - Venezuela','Adaptable al tropico','Mediana',NULL,NULL,NULL,NULL),(90,'Jersey','JER','Beige','Doble','Bos Taurus','Reino Unido','Alta eficiencia','Pequeña',NULL,NULL,NULL,NULL),(91,'Guernsey','GUE','Dorado','Doble','Bos Taurus','Reino Unido','Calidad de leche','Pequeña',NULL,NULL,NULL,NULL),(92,'Ayrshire','AYR','Rojo y blano','Doble','Bos Taurus','Escocia','Adaptable a clima frio','Mediana',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `composicion_raza` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuerno`
--

DROP TABLE IF EXISTS `cuerno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cuerno` (
  `id_Cuernos` int NOT NULL AUTO_INCREMENT,
  `fk_palpacion_id` int NOT NULL,
  `cuerno_tamano` float DEFAULT NULL,
  `cuerno_medicion` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cuerno_lado` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `iu_plano` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado_sano` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `patologia_nombre` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `patologia_descripcion` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_Cuernos`),
  KEY `fk_cuerno_palpacion` (`fk_palpacion_id`),
  CONSTRAINT `fk_cuerno_palpacion` FOREIGN KEY (`fk_palpacion_id`) REFERENCES `palpacion` (`palpacion_id`),
  CONSTRAINT `ck_estado_sano` CHECK ((`estado_sano` in (_utf8mb4'Flacidos',_utf8mb4'Semitonicos',_utf8mb4'Tonicos'))),
  CONSTRAINT `ck_IUPlano` CHECK ((`iu_plano` in (_utf8mb4'I',_utf8mb4'II',_utf8mb4'III',_utf8mb4'IV')))
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuerno`
--

LOCK TABLES `cuerno` WRITE;
/*!40000 ALTER TABLE `cuerno` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuerno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dia_palpacion`
--

DROP TABLE IF EXISTS `dia_palpacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dia_palpacion` (
  `dia_id` int NOT NULL AUTO_INCREMENT,
  `dia_dias` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`dia_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dia_palpacion`
--

LOCK TABLES `dia_palpacion` WRITE;
/*!40000 ALTER TABLE `dia_palpacion` DISABLE KEYS */;
INSERT INTO `dia_palpacion` VALUES (8,'30d'),(9,'60d'),(10,'90d'),(11,'120d'),(12,'150d'),(13,'180d'),(14,'270d');
/*!40000 ALTER TABLE `dia_palpacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `diagnostico`
--

DROP TABLE IF EXISTS `diagnostico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diagnostico` (
  `diagnostico_id` int NOT NULL AUTO_INCREMENT,
  `diagnostico_descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `diagnostico_tipo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `diagnostico_fecha` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fk_etapa_animal_anid` int NOT NULL,
  `fk_etapa_animal_etid` int NOT NULL,
  PRIMARY KEY (`diagnostico_id`),
  KEY `fk_etapa_animal` (`fk_etapa_animal_anid`,`fk_etapa_animal_etid`),
  CONSTRAINT `fk_etapa_animal` FOREIGN KEY (`fk_etapa_animal_anid`, `fk_etapa_animal_etid`) REFERENCES `etapa_animal` (`etan_animal_id`, `etan_etapa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnostico`
--

LOCK TABLES `diagnostico` WRITE;
/*!40000 ALTER TABLE `diagnostico` DISABLE KEYS */;
/*!40000 ALTER TABLE `diagnostico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dosis`
--

DROP TABLE IF EXISTS `dosis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dosis` (
  `dosis_vacuna_id` int NOT NULL,
  `dosis_casa_id` int NOT NULL,
  `dosis_id` int NOT NULL AUTO_INCREMENT,
  `dosis_frecuencia` decimal(3,0) NOT NULL,
  `dosis_costo` decimal(20,2) DEFAULT NULL,
  `dosis_costo_frasco` decimal(20,2) DEFAULT NULL,
  `dosis_fecha_uso_ini` date NOT NULL,
  `dosis_fecha_uso_fin` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dosis_etapa_animal_anid` int NOT NULL,
  `dosis_etapa_animal_etid` int NOT NULL,
  PRIMARY KEY (`dosis_id`,`dosis_vacuna_id`,`dosis_casa_id`),
  KEY `fk_etapa_animal_dosis` (`dosis_etapa_animal_etid`,`dosis_etapa_animal_anid`),
  KEY `fk_vacuna_casa` (`dosis_vacuna_id`,`dosis_casa_id`),
  CONSTRAINT `fk_etapa_animal_dosis` FOREIGN KEY (`dosis_etapa_animal_etid`, `dosis_etapa_animal_anid`) REFERENCES `etapa_animal` (`etan_animal_id`, `etan_etapa_id`),
  CONSTRAINT `fk_vacuna_casa` FOREIGN KEY (`dosis_vacuna_id`, `dosis_casa_id`) REFERENCES `vacuna_casa` (`vc_vacuna_id`, `vc_casa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dosis`
--

LOCK TABLES `dosis` WRITE;
/*!40000 ALTER TABLE `dosis` DISABLE KEYS */;
/*!40000 ALTER TABLE `dosis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado_animal`
--

DROP TABLE IF EXISTS `estado_animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado_animal` (
  `esan_id` int NOT NULL AUTO_INCREMENT,
  `esan_fecha_ini` date NOT NULL,
  `esan_fecha_fin` date DEFAULT NULL,
  `esan_fk_estado_id` int NOT NULL,
  `esan_fk_id_animal` int NOT NULL,
  PRIMARY KEY (`esan_id`),
  KEY `fk_se_siente` (`esan_fk_id_animal`),
  KEY `fk_estado` (`esan_fk_estado_id`),
  CONSTRAINT `fk_estado` FOREIGN KEY (`esan_fk_estado_id`) REFERENCES `estado_salud` (`estado_id`),
  CONSTRAINT `fk_se_siente` FOREIGN KEY (`esan_fk_id_animal`) REFERENCES `animal` (`id_Animal`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_animal`
--

LOCK TABLES `estado_animal` WRITE;
/*!40000 ALTER TABLE `estado_animal` DISABLE KEYS */;
INSERT INTO `estado_animal` VALUES (13,'2024-01-01',NULL,15,14),(14,'2025-10-01',NULL,15,15),(15,'2025-10-12',NULL,15,16),(16,'2025-07-01',NULL,15,17);
/*!40000 ALTER TABLE `estado_animal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado_salud`
--

DROP TABLE IF EXISTS `estado_salud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado_salud` (
  `estado_id` int NOT NULL AUTO_INCREMENT,
  `estado_nombre` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`estado_id`),
  CONSTRAINT `check_estado_nombre` CHECK (regexp_like(`estado_nombre`,_utf8mb4'^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$'))
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_salud`
--

LOCK TABLES `estado_salud` WRITE;
/*!40000 ALTER TABLE `estado_salud` DISABLE KEYS */;
INSERT INTO `estado_salud` VALUES (15,'Sano'),(16,'Enfermo'),(17,'Muerto'),(18,'Servicio'),(19,'Gestacion');
/*!40000 ALTER TABLE `estado_salud` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `etapa`
--

DROP TABLE IF EXISTS `etapa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `etapa` (
  `etapa_id` int NOT NULL AUTO_INCREMENT,
  `etapa_nombre` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `etapa_edad_ini` int NOT NULL,
  `etapa_edad_fin` int DEFAULT NULL,
  `etapa_fk_tipo_animal_id` int NOT NULL,
  `etapa_sexo` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`etapa_id`),
  KEY `fk_etapas` (`etapa_fk_tipo_animal_id`),
  CONSTRAINT `fk_etapas` FOREIGN KEY (`etapa_fk_tipo_animal_id`) REFERENCES `tipo_animal` (`tipo_animal_id`),
  CONSTRAINT `ck_etapa_edad_diff` CHECK ((`etapa_edad_fin` > `etapa_edad_ini`))
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etapa`
--

LOCK TABLES `etapa` WRITE;
/*!40000 ALTER TABLE `etapa` DISABLE KEYS */;
INSERT INTO `etapa` VALUES (15,'Becerro',0,365,3,'M'),(16,'Becerra',0,365,3,'H'),(17,'Maute',365,730,3,'M'),(18,'Mauta',0,365,3,'H'),(19,'Novillo',730,913,3,'M'),(20,'Novilla',0,365,3,'H'),(21,'Toro',913,NULL,3,'M'),(22,'Vaca',913,NULL,3,'H'),(23,'Bucerro',0,365,4,'M'),(24,'Bucerra',0,365,4,'H'),(25,'Añojo',365,730,4,'M'),(26,'Añoja',0,365,4,'H'),(27,'Butoro',730,NULL,4,'M'),(28,'Bufala',913,NULL,4,'H');
/*!40000 ALTER TABLE `etapa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `etapa_animal`
--

DROP TABLE IF EXISTS `etapa_animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `etapa_animal` (
  `etan_etapa_id` int NOT NULL,
  `etan_animal_id` int NOT NULL,
  `etan_fecha_ini` date NOT NULL,
  `etan_fecha_fin` date DEFAULT NULL,
  PRIMARY KEY (`etan_animal_id`,`etan_etapa_id`),
  KEY `fk_desarrolla` (`etan_animal_id`),
  KEY `fk_crece` (`etan_etapa_id`),
  CONSTRAINT `fk_crece` FOREIGN KEY (`etan_etapa_id`) REFERENCES `etapa` (`etapa_id`),
  CONSTRAINT `fk_desarrolla` FOREIGN KEY (`etan_animal_id`) REFERENCES `animal` (`id_Animal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etapa_animal`
--

LOCK TABLES `etapa_animal` WRITE;
/*!40000 ALTER TABLE `etapa_animal` DISABLE KEYS */;
INSERT INTO `etapa_animal` VALUES (16,14,'2024-01-01',NULL),(24,15,'2025-10-01',NULL),(24,16,'2025-10-12',NULL),(26,17,'2025-07-01',NULL);
/*!40000 ALTER TABLE `etapa_animal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finca`
--

DROP TABLE IF EXISTS `finca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `finca` (
  `id_Finca` int NOT NULL AUTO_INCREMENT,
  `id_Propietario` bigint unsigned NOT NULL,
  `Nombre` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Explotacion_Tipo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `archivado` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_Finca`),
  KEY `id_Propietario` (`id_Propietario`),
  CONSTRAINT `Finca_ibfk_1` FOREIGN KEY (`id_Propietario`) REFERENCES `propietario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `finca`
--

LOCK TABLES `finca` WRITE;
/*!40000 ALTER TABLE `finca` DISABLE KEYS */;
INSERT INTO `finca` VALUES (15,7,'Finca La Nueva Esperanza','Bovinos y Porcinos',0,'2025-07-28 04:59:21','2025-07-28 05:00:31'),(16,6,'Finca La Romeria','Bovinos',0,'2025-07-28 19:37:02','2025-07-28 19:37:02'),(17,7,'Finca Offline Updated','Bovinos y Porcinos',0,'2025-08-16 18:03:03','2025-08-16 18:19:31'),(18,7,'Finca Offile 2 Updated','Bovinos',0,'2025-08-16 21:36:33','2025-08-16 21:39:34');
/*!40000 ALTER TABLE `finca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `foliculo`
--

DROP TABLE IF EXISTS `foliculo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `foliculo` (
  `foliculo_id` int NOT NULL AUTO_INCREMENT,
  `foliculo_nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foliculo_siglas` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`foliculo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foliculo`
--

LOCK TABLES `foliculo` WRITE;
/*!40000 ALTER TABLE `foliculo` DISABLE KEYS */;
INSERT INTO `foliculo` VALUES (6,'Foliculo Ovario Derecho','FOD',NULL,NULL),(7,'Foliculo Ovario Izquierdo','FOI',NULL,NULL),(8,'Cuerpo Luteo Ovarico Derecho','CLOD',NULL,NULL),(9,'Cuerpo Luteo Ovarico Izquierdo','CLOI',NULL,NULL),(10,'Sin estructura Palpable','SEP',NULL,NULL),(11,'Foliculo Ovario Derecho','FOD',NULL,NULL),(12,'Foliculo Ovario Izquierdo','FOI',NULL,NULL),(13,'Cuerpo Luteo Ovarico Derecho','CLOD',NULL,NULL),(14,'Cuerpo Luteo Ovarico Izquierdo','CLOI',NULL,NULL),(15,'Sin estructura Palpable','SEP',NULL,NULL),(16,'Foliculo Ovario Derecho','FOD',NULL,NULL),(17,'Foliculo Ovario Izquierdo','FOI',NULL,NULL),(18,'Cuerpo Luteo Ovarico Derecho','CLOD',NULL,NULL),(19,'Cuerpo Luteo Ovarico Izquierdo','CLOI',NULL,NULL),(20,'Sin estructura Palpable','SEP',NULL,NULL);
/*!40000 ALTER TABLE `foliculo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hierro`
--

DROP TABLE IF EXISTS `hierro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hierro` (
  `id_Hierro` int NOT NULL AUTO_INCREMENT,
  `identificador` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_Finca` int NOT NULL,
  `id_Propietario` bigint unsigned NOT NULL,
  `Hierro_Imagen` blob,
  `Hierro_QR` blob,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_Hierro`),
  KEY `id_Finca` (`id_Finca`),
  KEY `id_Propietario` (`id_Propietario`),
  CONSTRAINT `Hierro_ibfk_1` FOREIGN KEY (`id_Finca`) REFERENCES `finca` (`id_Finca`),
  CONSTRAINT `Hierro_ibfk_2` FOREIGN KEY (`id_Propietario`) REFERENCES `propietario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hierro`
--

LOCK TABLES `hierro` WRITE;
/*!40000 ALTER TABLE `hierro` DISABLE KEYS */;
/*!40000 ALTER TABLE `hierro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historico_aplicacion`
--

DROP TABLE IF EXISTS `historico_aplicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historico_aplicacion` (
  `id_ha` int NOT NULL AUTO_INCREMENT,
  `ha_vacuna_id` int NOT NULL,
  `ha_casa_id` int NOT NULL,
  `ha_dosis_id` int NOT NULL,
  `fecha_inyeccion` date NOT NULL,
  PRIMARY KEY (`id_ha`),
  KEY `fk_dosis` (`ha_dosis_id`,`ha_vacuna_id`,`ha_casa_id`),
  CONSTRAINT `fk_dosis` FOREIGN KEY (`ha_dosis_id`, `ha_vacuna_id`, `ha_casa_id`) REFERENCES `dosis` (`dosis_id`, `dosis_vacuna_id`, `dosis_casa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historico_aplicacion`
--

LOCK TABLES `historico_aplicacion` WRITE;
/*!40000 ALTER TABLE `historico_aplicacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `historico_aplicacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `indices_corporales`
--

DROP TABLE IF EXISTS `indices_corporales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `indices_corporales` (
  `id_Indice` int NOT NULL AUTO_INCREMENT,
  `Anamorfosis` float DEFAULT NULL,
  `Corporal` float DEFAULT NULL,
  `Pelviano` float DEFAULT NULL,
  `Proporcionalidad` float DEFAULT NULL,
  `Dactilo_Toracico` float DEFAULT NULL,
  `Pelviano_Transversal` float DEFAULT NULL,
  `Pelviano_Longitudinal` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `indice_etapa_anid` int NOT NULL,
  `indice_etapa_etid` int NOT NULL,
  PRIMARY KEY (`id_Indice`),
  KEY `indice_etapa_animal` (`indice_etapa_anid`,`indice_etapa_etid`),
  CONSTRAINT `indice_etapa_animal` FOREIGN KEY (`indice_etapa_anid`, `indice_etapa_etid`) REFERENCES `etapa_animal` (`etan_animal_id`, `etan_etapa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `indices_corporales`
--

LOCK TABLES `indices_corporales` WRITE;
/*!40000 ALTER TABLE `indices_corporales` DISABLE KEYS */;
/*!40000 ALTER TABLE `indices_corporales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario_general`
--

DROP TABLE IF EXISTS `inventario_general`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario_general` (
  `id_Inv` int NOT NULL AUTO_INCREMENT,
  `id_Finca` int NOT NULL,
  `Num_Personal` int DEFAULT NULL,
  `Fecha_Inventario` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_Inv`),
  KEY `id_Finca` (`id_Finca`),
  CONSTRAINT `Inventario_General_ibfk_1` FOREIGN KEY (`id_Finca`) REFERENCES `finca` (`id_Finca`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario_general`
--

LOCK TABLES `inventario_general` WRITE;
/*!40000 ALTER TABLE `inventario_general` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventario_general` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario_vacuno`
--

DROP TABLE IF EXISTS `inventario_vacuno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario_vacuno` (
  `id_Inv_V` int NOT NULL AUTO_INCREMENT,
  `id_Finca` int NOT NULL,
  `Num_Becerra` int DEFAULT NULL,
  `Num_Mauta` int DEFAULT NULL,
  `Num_Novilla` int DEFAULT NULL,
  `Num_Vaca` int DEFAULT NULL,
  `Num_Becerro` int DEFAULT NULL,
  `Num_Maute` int DEFAULT NULL,
  `Num_Torete` int DEFAULT NULL,
  `Num_Toro` int DEFAULT NULL,
  `Fecha_Inventario` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_Inv_V`),
  KEY `id_Finca` (`id_Finca`),
  CONSTRAINT `Inventario_Vacuno_ibfk_1` FOREIGN KEY (`id_Finca`) REFERENCES `finca` (`id_Finca`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario_vacuno`
--

LOCK TABLES `inventario_vacuno` WRITE;
/*!40000 ALTER TABLE `inventario_vacuno` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventario_vacuno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lactancia`
--

DROP TABLE IF EXISTS `lactancia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lactancia` (
  `lactancia_id` int NOT NULL AUTO_INCREMENT,
  `lactancia_fecha_inicio` date DEFAULT NULL,
  `Lactancia_fecha_fin` date DEFAULT NULL,
  `lactancia_secado` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lactancia_etapa_anid` int NOT NULL,
  `lactancia_etapa_etid` int NOT NULL,
  PRIMARY KEY (`lactancia_id`),
  KEY `lactancia_etapa_animal` (`lactancia_etapa_anid`,`lactancia_etapa_etid`),
  CONSTRAINT `lactancia_etapa_animal` FOREIGN KEY (`lactancia_etapa_anid`, `lactancia_etapa_etid`) REFERENCES `etapa_animal` (`etan_animal_id`, `etan_etapa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lactancia`
--

LOCK TABLES `lactancia` WRITE;
/*!40000 ALTER TABLE `lactancia` DISABLE KEYS */;
INSERT INTO `lactancia` VALUES (5,'2025-10-12',NULL,'2025-10-13','2025-10-13 23:22:01','2025-10-13 23:22:01',15,24),(6,'2025-10-14',NULL,'2025-10-14','2025-10-15 20:39:37','2025-10-15 20:39:37',16,24),(7,'2025-10-08',NULL,'2025-10-14','2025-10-16 19:43:52','2025-10-16 19:43:52',14,16);
/*!40000 ALTER TABLE `lactancia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leche`
--

DROP TABLE IF EXISTS `leche`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leche` (
  `leche_id` int NOT NULL AUTO_INCREMENT,
  `leche_fecha_pesaje` date DEFAULT NULL,
  `leche_pesaje_Total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `leche_lactancia_id` int NOT NULL,
  PRIMARY KEY (`leche_id`),
  KEY `fk_lactancia` (`leche_lactancia_id`),
  CONSTRAINT `fk_lactancia` FOREIGN KEY (`leche_lactancia_id`) REFERENCES `lactancia` (`lactancia_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leche`
--

LOCK TABLES `leche` WRITE;
/*!40000 ALTER TABLE `leche` DISABLE KEYS */;
INSERT INTO `leche` VALUES (4,'2025-10-13',30.00,'2025-10-13 23:22:29','2025-10-13 23:22:29',5),(5,'2025-10-13',10.00,'2025-10-13 23:23:41','2025-10-13 23:23:41',5),(6,'2025-10-15',100.00,'2025-10-15 20:35:53','2025-10-15 20:35:53',5),(7,'2025-10-15',30.00,'2025-10-15 20:40:06','2025-10-15 20:40:06',6);
/*!40000 ALTER TABLE `leche` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medidas_corporales`
--

DROP TABLE IF EXISTS `medidas_corporales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medidas_corporales` (
  `id_Medida` int NOT NULL AUTO_INCREMENT,
  `Altura_HC` float DEFAULT NULL,
  `Altura_HG` float DEFAULT NULL,
  `Perimetro_PT` float DEFAULT NULL,
  `Perimetro_PCA` float DEFAULT NULL,
  `Longitud_LC` float DEFAULT NULL,
  `Longitud_LG` float DEFAULT NULL,
  `Anchura_AG` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `medida_etapa_anid` int NOT NULL,
  `medida_etapa_etid` int NOT NULL,
  PRIMARY KEY (`id_Medida`),
  KEY `medida_etapa_animal` (`medida_etapa_anid`,`medida_etapa_etid`),
  CONSTRAINT `medida_etapa_animal` FOREIGN KEY (`medida_etapa_anid`, `medida_etapa_etid`) REFERENCES `etapa_animal` (`etan_animal_id`, `etan_etapa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medidas_corporales`
--

LOCK TABLES `medidas_corporales` WRITE;
/*!40000 ALTER TABLE `medidas_corporales` DISABLE KEYS */;
/*!40000 ALTER TABLE `medidas_corporales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimiento_rebano`
--

DROP TABLE IF EXISTS `movimiento_rebano`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimiento_rebano` (
  `id_Movimiento` int NOT NULL AUTO_INCREMENT,
  `id_Finca` int NOT NULL,
  `id_Rebano` int NOT NULL,
  `Rebano_Destino` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_Finca_Destino` int NOT NULL,
  `id_Rebano_Destino` int NOT NULL,
  `Comentario` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_Movimiento`),
  KEY `id_Rebano` (`id_Rebano`),
  KEY `id_Finca` (`id_Finca`),
  CONSTRAINT `Movimiento_Rebano_ibfk_1` FOREIGN KEY (`id_Rebano`) REFERENCES `rebano` (`id_Rebano`),
  CONSTRAINT `Movimiento_Rebano_ibfk_2` FOREIGN KEY (`id_Finca`) REFERENCES `finca` (`id_Finca`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimiento_rebano`
--

LOCK TABLES `movimiento_rebano` WRITE;
/*!40000 ALTER TABLE `movimiento_rebano` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimiento_rebano` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimiento_rebano_animal`
--

DROP TABLE IF EXISTS `movimiento_rebano_animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimiento_rebano_animal` (
  `id_Animal` int NOT NULL,
  `id_Movimiento` int NOT NULL,
  `Estado` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_Animal`,`id_Movimiento`),
  KEY `FK_id_Movimiento_movimiento_rebano_animal` (`id_Movimiento`),
  CONSTRAINT `FK_id_Animal_movimiento_rebano_animal` FOREIGN KEY (`id_Animal`) REFERENCES `animal` (`id_Animal`),
  CONSTRAINT `FK_id_Movimiento_movimiento_rebano_animal` FOREIGN KEY (`id_Movimiento`) REFERENCES `movimiento_rebano` (`id_Movimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimiento_rebano_animal`
--

LOCK TABLES `movimiento_rebano_animal` WRITE;
/*!40000 ALTER TABLE `movimiento_rebano_animal` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimiento_rebano_animal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ovario`
--

DROP TABLE IF EXISTS `ovario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ovario` (
  `ovario_id` int NOT NULL AUTO_INCREMENT,
  `ovario_medida` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ovario_tamano` decimal(6,2) DEFAULT NULL,
  `ovario_lado` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ovario_palpacion_id` int NOT NULL,
  PRIMARY KEY (`ovario_id`),
  KEY `fk_palpacion_ovario` (`ovario_palpacion_id`),
  CONSTRAINT `fk_palpacion_ovario` FOREIGN KEY (`ovario_palpacion_id`) REFERENCES `palpacion` (`palpacion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ovario`
--

LOCK TABLES `ovario` WRITE;
/*!40000 ALTER TABLE `ovario` DISABLE KEYS */;
/*!40000 ALTER TABLE `ovario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ovario_foliculo`
--

DROP TABLE IF EXISTS `ovario_foliculo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ovario_foliculo` (
  `fovo_id` int NOT NULL AUTO_INCREMENT,
  `fovo_tamano` decimal(6,2) NOT NULL,
  `fovo_ovario_fase` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fovo_foliculo_id` int NOT NULL,
  `fovo_ovario_id` int NOT NULL,
  PRIMARY KEY (`fovo_id`),
  KEY `fk_foliculo` (`fovo_foliculo_id`),
  KEY `fk_ovario` (`fovo_ovario_id`),
  CONSTRAINT `fk_foliculo` FOREIGN KEY (`fovo_foliculo_id`) REFERENCES `foliculo` (`foliculo_id`),
  CONSTRAINT `fk_ovario` FOREIGN KEY (`fovo_ovario_id`) REFERENCES `ovario` (`ovario_id`),
  CONSTRAINT `ck_ovario_fase` CHECK ((`fovo_ovario_fase` in (_utf8mb4'Folicular',_utf8mb4'Luteal',_utf8mb4'SEP')))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ovario_foliculo`
--

LOCK TABLES `ovario_foliculo` WRITE;
/*!40000 ALTER TABLE `ovario_foliculo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ovario_foliculo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `palpacion`
--

DROP TABLE IF EXISTS `palpacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `palpacion` (
  `palpacion_id` int NOT NULL AUTO_INCREMENT,
  `id_Tecnico` int DEFAULT NULL,
  `palpacion_tipo` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `palpacion_fecha` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `palpacion_etapa_anid` int NOT NULL,
  `palpacion_etapa_etid` int NOT NULL,
  PRIMARY KEY (`palpacion_id`),
  KEY `palpacion_etapa_animal` (`palpacion_etapa_anid`,`palpacion_etapa_etid`),
  KEY `Palpacion_ibfk_2` (`id_Tecnico`),
  CONSTRAINT `palpacion_etapa_animal` FOREIGN KEY (`palpacion_etapa_anid`, `palpacion_etapa_etid`) REFERENCES `etapa_animal` (`etan_animal_id`, `etan_etapa_id`),
  CONSTRAINT `Palpacion_ibfk_2` FOREIGN KEY (`id_Tecnico`) REFERENCES `personal_finca` (`id_Tecnico`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `palpacion`
--

LOCK TABLES `palpacion` WRITE;
/*!40000 ALTER TABLE `palpacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `palpacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',6,'GanaderaSoft API Token','888dfd8a272136396b079c97f1fc015264da7739c52414a51060bdb3ed79b7ed','[\"*\"]',NULL,NULL,'2025-07-28 04:55:43','2025-07-28 04:55:43'),(2,'App\\Models\\User',6,'GanaderaSoft API Token','7ea24759226852101f61dfeeda8775f2e98f9c74a886a879c2009bebf36dd5b7','[\"*\"]',NULL,NULL,'2025-07-28 04:56:17','2025-07-28 04:56:17'),(3,'App\\Models\\User',6,'GanaderaSoft API Token','6d95ad15585d91dfb4d2857080977185545b98c99ef3bde5b915b5d43a33ea7a','[\"*\"]','2025-07-28 05:01:11',NULL,'2025-07-28 04:57:51','2025-07-28 05:01:11'),(4,'App\\Models\\User',6,'GanaderaSoft API Token','a080a05957d8b99516047f2204eaa5629a9a88dc50d88cf3fcfaa108e6ca7e9d','[\"*\"]','2025-07-28 19:47:57',NULL,'2025-07-28 19:15:47','2025-07-28 19:47:57'),(5,'App\\Models\\User',6,'GanaderaSoft API Token','67ceb80d1e173b47a195708fc995ee3ba0bebca723c4f029ddc7b481f1b02b4e','[\"*\"]','2025-08-16 18:41:00',NULL,'2025-08-16 17:55:49','2025-08-16 18:41:00'),(7,'App\\Models\\User',6,'GanaderaSoft API Token','a8fd1d7b2ca8ad5f1de41f2b49c082a7f95ec5030912687399a48ec93b868693','[\"*\"]',NULL,NULL,'2025-08-16 21:51:24','2025-08-16 21:51:24'),(8,'App\\Models\\User',6,'GanaderaSoft API Token','44a2086134843646ddb7e6597e72e33bbe87aa478ec5494c7a6d9e030361773c','[\"*\"]',NULL,NULL,'2025-08-16 22:26:38','2025-08-16 22:26:38'),(11,'App\\Models\\User',6,'GanaderaSoft API Token','2474dc92a1715280e707fec7ce43aed6993537f62fe2a699e26744afc3865709','[\"*\"]','2025-08-17 16:12:29',NULL,'2025-08-16 22:41:54','2025-08-17 16:12:29'),(13,'App\\Models\\User',6,'GanaderaSoft API Token','695ecb6f5b8047c88d5bd4a3532f3d4a5ea813e32eef050bbc785842e368b24e','[\"*\"]','2025-08-17 00:30:37',NULL,'2025-08-16 23:51:26','2025-08-17 00:30:37'),(14,'App\\Models\\User',6,'GanaderaSoft API Token','c12613ab4a4e6aec1c2c278057ab9f88cae8e0352de56a79c0392c9fc32a9e6d','[\"*\"]','2025-08-17 01:51:07',NULL,'2025-08-17 01:07:27','2025-08-17 01:51:07'),(16,'App\\Models\\User',6,'GanaderaSoft API Token','742ea13ab2be65e52e7fd09e116c6e54f5b0590b16ee6263b34dd16b704ff35a','[\"*\"]','2025-08-17 03:15:16',NULL,'2025-08-17 03:08:40','2025-08-17 03:15:16'),(17,'App\\Models\\User',6,'GanaderaSoft API Token','b77c9daf58b94e2d0207e39ae4c532523e2e0b42f9950aa88a27a3e14ee2affc','[\"*\"]','2025-08-17 16:13:45',NULL,'2025-08-17 16:12:25','2025-08-17 16:13:45'),(18,'App\\Models\\User',6,'GanaderaSoft API Token','a19b067eae94820f483433c8b1c752049fcfb2f6f21afc691d96101b22c72ab3','[\"*\"]','2025-10-13 23:23:52',NULL,'2025-10-13 22:53:41','2025-10-13 23:23:52'),(19,'App\\Models\\User',6,'GanaderaSoft API Token','bc2923f5d8f9cbcd33b12f1a31b8f452518fa55fa5a23543f7e1407b50e54f83','[\"*\"]','2025-10-15 20:27:55',NULL,'2025-10-15 16:19:16','2025-10-15 20:27:55'),(21,'App\\Models\\User',6,'GanaderaSoft API Token','f174617586bf409412372b66eb993e41437f47ad38fcdad4439329a900d13683','[\"*\"]','2025-10-16 19:34:22',NULL,'2025-10-16 19:30:03','2025-10-16 19:34:22'),(22,'App\\Models\\User',6,'GanaderaSoft API Token','7f1f7b6c3d89183aeaed8a94514b5d5f63e0d0671388417d67c6ee0890eb015f','[\"*\"]','2025-10-16 19:44:32',NULL,'2025-10-16 19:37:33','2025-10-16 19:44:32'),(23,'App\\Models\\User',6,'GanaderaSoft API Token','076a6eb1db8b63043bae85c146db9f8e055c10bd95006b744ed59fbd7370dd20','[\"*\"]','2025-10-16 23:11:12',NULL,'2025-10-16 19:45:43','2025-10-16 23:11:12'),(24,'App\\Models\\User',6,'GanaderaSoft API Token','8797bed6103cb988b97c5608e74adef599a90ea25c685d360b4cbcca3cc13354','[\"*\"]',NULL,NULL,'2025-10-17 15:22:00','2025-10-17 15:22:00'),(25,'App\\Models\\User',6,'GanaderaSoft API Token','245d2ef53b741d62ef09af9da8ce7f30aaa6872fb6340f926b5ac786c17f3d8d','[\"*\"]','2025-10-17 15:23:24',NULL,'2025-10-17 15:22:43','2025-10-17 15:23:24');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_finca`
--

DROP TABLE IF EXISTS `personal_finca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_finca` (
  `id_Tecnico` int NOT NULL AUTO_INCREMENT,
  `id_Finca` int NOT NULL,
  `Cedula` int NOT NULL,
  `Nombre` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Apellido` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Telefono` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Correo` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Tipo_Trabajador` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_Tecnico`),
  KEY `id_Finca` (`id_Finca`),
  CONSTRAINT `Personal_Finca_ibfk_1` FOREIGN KEY (`id_Finca`) REFERENCES `finca` (`id_Finca`)
) ENGINE=InnoDB AUTO_INCREMENT=1008 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_finca`
--

LOCK TABLES `personal_finca` WRITE;
/*!40000 ALTER TABLE `personal_finca` DISABLE KEYS */;
INSERT INTO `personal_finca` VALUES (1004,16,10123001,'Jose','Gil','0424525002','jgil@ucv.com','Tecnico','2025-10-13 23:05:18','2025-10-16 19:43:51'),(1005,16,17001003,'Marina','Rodriguez','04267170821','m.rodriguez@ucv.com','Veterinario','2025-10-15 16:29:59','2025-10-15 16:29:59'),(1006,16,15001002,'Julio','Martinez','042463256852','jmarrinez@ucv.com','Operario','2025-10-15 20:38:41','2025-10-15 20:38:41'),(1007,16,125832641,'Josue','Cabrera','02451325482','jcabrera@ucv.com','Operario','2025-10-16 19:43:51','2025-10-16 19:43:51');
/*!40000 ALTER TABLE `personal_finca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peso_corporal`
--

DROP TABLE IF EXISTS `peso_corporal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peso_corporal` (
  `id_Peso` int NOT NULL AUTO_INCREMENT,
  `Fecha_Peso` date DEFAULT NULL,
  `Peso` float DEFAULT NULL,
  `Comentario` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `peso_etapa_anid` int NOT NULL,
  `peso_etapa_etid` int NOT NULL,
  PRIMARY KEY (`id_Peso`),
  KEY `peso_etapa_animal` (`peso_etapa_anid`,`peso_etapa_etid`),
  CONSTRAINT `peso_etapa_animal` FOREIGN KEY (`peso_etapa_anid`, `peso_etapa_etid`) REFERENCES `etapa_animal` (`etan_animal_id`, `etan_etapa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peso_corporal`
--

LOCK TABLES `peso_corporal` WRITE;
/*!40000 ALTER TABLE `peso_corporal` DISABLE KEYS */;
INSERT INTO `peso_corporal` VALUES (6,'2025-10-13',1200,'Nuevo peso de marianita','2025-10-13 23:21:21','2025-10-13 23:21:21',15,24),(7,'2025-10-15',1200,NULL,'2025-10-15 20:37:42','2025-10-15 20:37:42',16,24);
/*!40000 ALTER TABLE `peso_corporal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prenez_dia`
--

DROP TABLE IF EXISTS `prenez_dia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prenez_dia` (
  `prdi_id` int NOT NULL AUTO_INCREMENT,
  `prdi_tamano` decimal(6,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `prdi_dia_id` int NOT NULL,
  `prdi_palpacion_id` int NOT NULL,
  PRIMARY KEY (`prdi_id`),
  KEY `fk_palpacion_prenez` (`prdi_palpacion_id`),
  KEY `fk_dia_palpacion` (`prdi_dia_id`),
  CONSTRAINT `fk_dia_palpacion` FOREIGN KEY (`prdi_dia_id`) REFERENCES `dia_palpacion` (`dia_id`),
  CONSTRAINT `fk_palpacion_prenez` FOREIGN KEY (`prdi_palpacion_id`) REFERENCES `palpacion` (`palpacion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prenez_dia`
--

LOCK TABLES `prenez_dia` WRITE;
/*!40000 ALTER TABLE `prenez_dia` DISABLE KEYS */;
/*!40000 ALTER TABLE `prenez_dia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `propietario`
--

DROP TABLE IF EXISTS `propietario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `propietario` (
  `id` bigint unsigned NOT NULL,
  `id_Personal` int DEFAULT NULL,
  `Nombre` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Apellido` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Telefono` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `archivado` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `Fk_Users_P` FOREIGN KEY (`id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `propietario`
--

LOCK TABLES `propietario` WRITE;
/*!40000 ALTER TABLE `propietario` DISABLE KEYS */;
INSERT INTO `propietario` VALUES (6,17873216,'Leonel','Romero','04140659739',0,NULL,NULL),(7,16000001,'Yosly','Hernandez','04140659739',0,NULL,NULL);
/*!40000 ALTER TABLE `propietario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rebano`
--

DROP TABLE IF EXISTS `rebano`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rebano` (
  `id_Rebano` int NOT NULL AUTO_INCREMENT,
  `id_Finca` int NOT NULL,
  `Nombre` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `archivado` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_Rebano`),
  KEY `id_Finca` (`id_Finca`),
  CONSTRAINT `Rebano_ibfk_1` FOREIGN KEY (`id_Finca`) REFERENCES `finca` (`id_Finca`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rebano`
--

LOCK TABLES `rebano` WRITE;
/*!40000 ALTER TABLE `rebano` DISABLE KEYS */;
INSERT INTO `rebano` VALUES (6,16,'Mi Rebaño',0,'2025-08-16 18:06:44','2025-08-16 18:21:03'),(7,16,'Rebaño Norte',0,'2025-10-15 20:32:44','2025-10-15 20:32:44');
/*!40000 ALTER TABLE `rebano` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registro_celo`
--

DROP TABLE IF EXISTS `registro_celo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registro_celo` (
  `celo_id` int NOT NULL AUTO_INCREMENT,
  `celo_fecha` date NOT NULL,
  `celo_observacon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `celo_etapa_anid` int NOT NULL,
  `celo_etapa_etid` int NOT NULL,
  PRIMARY KEY (`celo_id`),
  KEY `celo_etapa_animal` (`celo_etapa_anid`,`celo_etapa_etid`),
  CONSTRAINT `celo_etapa_animal` FOREIGN KEY (`celo_etapa_anid`, `celo_etapa_etid`) REFERENCES `etapa_animal` (`etan_animal_id`, `etan_etapa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registro_celo`
--

LOCK TABLES `registro_celo` WRITE;
/*!40000 ALTER TABLE `registro_celo` DISABLE KEYS */;
/*!40000 ALTER TABLE `registro_celo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reproduccion_animal`
--

DROP TABLE IF EXISTS `reproduccion_animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reproduccion_animal` (
  `repro_id` int NOT NULL AUTO_INCREMENT,
  `repro_fecha_reproduccion` date NOT NULL,
  `repro_tipo_reproduccion` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `repro_observacion` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `repro_etapa_anid` int NOT NULL,
  `repro_etapa_etid` int NOT NULL,
  PRIMARY KEY (`repro_id`),
  KEY `repro_servicio_id` (`repro_etapa_anid`,`repro_etapa_etid`),
  CONSTRAINT `repro_servicio_id` FOREIGN KEY (`repro_etapa_anid`, `repro_etapa_etid`) REFERENCES `etapa_animal` (`etan_animal_id`, `etan_etapa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reproduccion_animal`
--

LOCK TABLES `reproduccion_animal` WRITE;
/*!40000 ALTER TABLE `reproduccion_animal` DISABLE KEYS */;
/*!40000 ALTER TABLE `reproduccion_animal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `semen_toro`
--

DROP TABLE IF EXISTS `semen_toro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `semen_toro` (
  `semen_id` int NOT NULL AUTO_INCREMENT,
  `id_Toro` int NOT NULL,
  `semen_estado` tinyint(1) DEFAULT NULL,
  `semen_fecha` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`semen_id`),
  KEY `id_Toro` (`id_Toro`),
  CONSTRAINT `fk_semen_toro_animal` FOREIGN KEY (`id_Toro`) REFERENCES `animal` (`id_Animal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `semen_toro`
--

LOCK TABLES `semen_toro` WRITE;
/*!40000 ALTER TABLE `semen_toro` DISABLE KEYS */;
/*!40000 ALTER TABLE `semen_toro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio_animal`
--

DROP TABLE IF EXISTS `servicio_animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicio_animal` (
  `servicio_id` int NOT NULL AUTO_INCREMENT,
  `servicio_id_Animal` int NOT NULL,
  `servicio_semen_id` int DEFAULT NULL,
  `servicio_id_Tecnico` int DEFAULT NULL,
  `servicio_tipo` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `servicio_fecha` date DEFAULT NULL,
  `servicio_observacion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `servicio_celo_id` int DEFAULT NULL,
  PRIMARY KEY (`servicio_id`),
  KEY `FK_AnimalS` (`servicio_id_Animal`),
  KEY `FK_TecnicoS` (`servicio_id_Tecnico`),
  KEY `FK_ToroS` (`servicio_semen_id`),
  KEY `fk_celo` (`servicio_celo_id`),
  CONSTRAINT `FK_AnimalS` FOREIGN KEY (`servicio_id_Animal`) REFERENCES `animal` (`id_Animal`),
  CONSTRAINT `fk_celo` FOREIGN KEY (`servicio_celo_id`) REFERENCES `registro_celo` (`celo_id`),
  CONSTRAINT `FK_TecnicoS` FOREIGN KEY (`servicio_id_Tecnico`) REFERENCES `personal_finca` (`id_Tecnico`),
  CONSTRAINT `FK_ToroS` FOREIGN KEY (`servicio_semen_id`) REFERENCES `semen_toro` (`semen_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio_animal`
--

LOCK TABLES `servicio_animal` WRITE;
/*!40000 ALTER TABLE `servicio_animal` DISABLE KEYS */;
/*!40000 ALTER TABLE `servicio_animal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `terreno`
--

DROP TABLE IF EXISTS `terreno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `terreno` (
  `id_Terreno` int NOT NULL AUTO_INCREMENT,
  `id_Finca` int NOT NULL,
  `Superficie` float DEFAULT NULL,
  `Relieve` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Suelo_Textura` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ph_Suelo` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Precipitacion` float DEFAULT NULL,
  `Velocidad_Viento` float DEFAULT NULL,
  `Temp_Anual` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Temp_Min` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Temp_Max` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Radiacion` float DEFAULT NULL,
  `Fuente_Agua` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Caudal_Disponible` int DEFAULT NULL,
  `Riego_Metodo` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_Terreno`),
  KEY `id_Finca` (`id_Finca`),
  CONSTRAINT `Terreno_ibfk_1` FOREIGN KEY (`id_Finca`) REFERENCES `finca` (`id_Finca`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `terreno`
--

LOCK TABLES `terreno` WRITE;
/*!40000 ALTER TABLE `terreno` DISABLE KEYS */;
INSERT INTO `terreno` VALUES (15,18,12,'Ondulado','Franco','6',1200,15,'24','18','30',5.2,'Subterranea',1000,'Aspersión','2025-08-16 21:36:33','2025-08-16 21:39:34');
/*!40000 ALTER TABLE `terreno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_animal`
--

DROP TABLE IF EXISTS `tipo_animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_animal` (
  `tipo_animal_id` int NOT NULL AUTO_INCREMENT,
  `tipo_animal_nombre` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`tipo_animal_id`),
  CONSTRAINT `check_tipo_animal_nombre` CHECK (regexp_like(`tipo_animal_nombre`,_utf8mb4'^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$'))
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_animal`
--

LOCK TABLES `tipo_animal` WRITE;
/*!40000 ALTER TABLE `tipo_animal` DISABLE KEYS */;
INSERT INTO `tipo_animal` VALUES (3,'Vacuno'),(4,'Bufala');
/*!40000 ALTER TABLE `tipo_animal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transcriptor`
--

DROP TABLE IF EXISTS `transcriptor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transcriptor` (
  `id` bigint unsigned NOT NULL,
  `id_Personal` int DEFAULT NULL,
  `Tipo_Transcriptor` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Nombre` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Apellido` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Telefono` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `archivado` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_UsersT` FOREIGN KEY (`id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transcriptor`
--

LOCK TABLES `transcriptor` WRITE;
/*!40000 ALTER TABLE `transcriptor` DISABLE KEYS */;
/*!40000 ALTER TABLE `transcriptor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tratamiento`
--

DROP TABLE IF EXISTS `tratamiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tratamiento` (
  `tratamiento_id` int NOT NULL AUTO_INCREMENT,
  `tratamiento_plan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tratamiento_fecha_ini` date NOT NULL,
  `tratamiento_fecha_fin` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tratamiento_diagnostico_id` int DEFAULT NULL,
  PRIMARY KEY (`tratamiento_id`),
  KEY `fk_tratamiento_diag` (`tratamiento_diagnostico_id`),
  CONSTRAINT `fk_tratamiento_diag` FOREIGN KEY (`tratamiento_diagnostico_id`) REFERENCES `diagnostico` (`diagnostico_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tratamiento`
--

LOCK TABLES `tratamiento` WRITE;
/*!40000 ALTER TABLE `tratamiento` DISABLE KEYS */;
/*!40000 ALTER TABLE `tratamiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user.png',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type_user` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (6,'Leonel Romero - Usuario','leoromerbric@gmail.com',NULL,'$2y$10$Ov9LmP7gvr1P/KCrEPWtSesXQ3w3MHYkCZfsPgcOXtpjAnnMNN9Ka','user.png',NULL,NULL,'2024-02-01 15:19:01','2024-02-01 15:19:01','propietario'),(7,'Ingeniero','ingeniero1.ganaderosoft@yopmail.com',NULL,'$2y$10$WcDcr1PS3nVlk4WkxiQQbe1dR/qgHObPxkYeL0fvz1yedV8nj/PPO','user.png',NULL,NULL,'2024-02-01 15:19:28','2024-02-01 15:19:28',NULL),(8,'Veterinario','veterinario1.ganaderosoft@yopmail.com',NULL,'$2y$10$L7sY2rZKCdgzinMDzJeM5.UZbby43xEy.AAiSrC3QLrRDraIeBUmG','user.png',NULL,NULL,'2024-02-01 15:20:02','2024-02-01 15:20:02',NULL),(9,'Asistente','asistente1.ganaderosoft@yopmail.com',NULL,'$2y$10$rGbyEcyp7n9M3BsapN.rPevRyQyhaHwj4JxmMzGBTLcRhJ1FbwWIy','user.png',NULL,NULL,'2024-02-01 15:22:15','2024-02-01 15:22:15',NULL),(13,'Yosly Hernandez','yosly@ucv.com',NULL,'$2y$10$Ov9LmP7gvr1P/KCrEPWtSesXQ3w3MHYkCZfsPgcOXtpjAnnMNN9Ka','user.png',NULL,NULL,'2024-03-08 17:57:28','2024-03-08 17:57:28','propietario');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacuna`
--

DROP TABLE IF EXISTS `vacuna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vacuna` (
  `vacuna_id` int NOT NULL AUTO_INCREMENT,
  `vacuna_nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`vacuna_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacuna`
--

LOCK TABLES `vacuna` WRITE;
/*!40000 ALTER TABLE `vacuna` DISABLE KEYS */;
INSERT INTO `vacuna` VALUES (1,'Aftosa',NULL,NULL),(2,'Rabia',NULL,NULL),(3,'Bucelosis',NULL,NULL),(4,'Leptospirosis',NULL,NULL),(5,'Clostridium',NULL,NULL),(6,'Estomatitis V',NULL,NULL);
/*!40000 ALTER TABLE `vacuna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacuna_casa`
--

DROP TABLE IF EXISTS `vacuna_casa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vacuna_casa` (
  `vc_vacuna_id` int NOT NULL,
  `vc_casa_id` int NOT NULL,
  `dosis_cantidad` decimal(10,2) NOT NULL,
  PRIMARY KEY (`vc_vacuna_id`,`vc_casa_id`),
  KEY `fk_casa_comercializadora` (`vc_casa_id`),
  CONSTRAINT `fk_casa_comercializadora` FOREIGN KEY (`vc_casa_id`) REFERENCES `casa_comercial` (`casa_id`),
  CONSTRAINT `fk_vacuna` FOREIGN KEY (`vc_vacuna_id`) REFERENCES `vacuna` (`vacuna_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacuna_casa`
--

LOCK TABLES `vacuna_casa` WRITE;
/*!40000 ALTER TABLE `vacuna_casa` DISABLE KEYS */;
INSERT INTO `vacuna_casa` VALUES (1,1,2.00),(1,5,2.00),(2,2,2.00),(2,6,2.00),(3,8,2.00),(4,3,5.00),(5,9,5.00),(6,4,3.00),(6,7,5.00);
/*!40000 ALTER TABLE `vacuna_casa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'ganaderasoft'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-17 15:01:53
