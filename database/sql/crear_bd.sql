-- ============================================================
-- Base de datos del Sprint 1: módulo de autenticación
-- Importar desde phpMyAdmin (XAMPP) -> pestaña "Importar"
-- ============================================================

CREATE DATABASE IF NOT EXISTS autenticacion
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE autenticacion;

CREATE TABLE IF NOT EXISTS usuario (
    id_usuario  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre      VARCHAR(100)    NOT NULL,
    correo      VARCHAR(150)    NOT NULL,
    password    VARCHAR(255)    NOT NULL,   -- aquí se guarda el hash, nunca el texto plano
    created_at  TIMESTAMP       NULL DEFAULT NULL,
    updated_at  TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id_usuario),
    UNIQUE KEY uq_usuario_correo (correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
