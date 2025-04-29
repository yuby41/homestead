#!/bin/bash

# Salir si algo crítico falla
set -e

echo "🚀 [BEFORE.SH] Iniciando preparación previa en Homestead..."

# Configurar APT para trabajar más rápido y no preguntar confirmaciones
echo "🔧 Configurando APT para acelerar actualizaciones..."
sudo sed -i 's/^#\(force-unsafe-io\)/\1/' /etc/dpkg/dpkg.cfg.d/extract 2>/dev/null || true

# Prevenir preguntas interactivas de dpkg (por si hay conflictos)
echo 'debconf debconf/frontend select Noninteractive' | sudo debconf-set-selections

# Actualizar lista de paquetes (rápido, sin mostrar errores si falla el label)
echo "🔄 Actualizando lista de paquetes silenciosamente..."
sudo apt-get update --allow-releaseinfo-change -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" || true

# Instalar utilidades esenciales (solo si no están)
ESSENTIALS=(curl wget vim unzip git software-properties-common)

for pkg in "${ESSENTIALS[@]}"; do
  if ! dpkg -s $pkg >/dev/null 2>&1; then
    echo "📦 Instalando utilidad esencial: $pkg"
    sudo apt-get install -y $pkg
  else
    echo "✅ Utilidad ya instalada: $pkg"
  fi
done

echo "✅ [BEFORE.SH] Preparación previa completada correctamente."
