<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorias = [
            'Archiveros', 'Automóviles', 'Balanzas', 'Baterías', 'Bicicletas',
            'Camioneta', 'Cámaras', 'Cámaras de Seguridad', 'Carros de Limpieza', 
            'Carros de Servicio', 'Centrifugas', 'Computadoras', 'Detectores de Humo',
            'Dispositivos de Red(Routers, Switches)', 'Equipos de Atletismo', 'Equipos de Baloncesto',
            'Equipos de Difusión', 'Equipos de Futbol', 'Equipos de Gimnasio', 'Equipos de Jardinería',
            'Equipos de Limpieza', 'Equipos de Medición', 'Equipos de Protección Personal',
            'Equipos de Refrigeración', 'Equipos de Telecomunicaciones', 'Equipos de Video Conferencia',
            'Escritorios', 'Escáneres', 'Estanterías', 'Estación de Carga', 'Estufas y Hornos',
            'Extintores', 'Fax', 'Fotocopiadoras', 'Generadores', 'Herramientas Eléctricas',
            'Herramientas Manuales', 'Impresoras','Intrumentos Musicales', 'Inversores', 'Lavavajillas', 'Maquinas de Escribir',
            'Mesas de Preparación', 'Microscopios', 'Montacargas', 'Motocicletas', 'Paneles Solares',
            'Patinetas Eléctricas', 'Proyectores', 'Servidores', 'Shedderes(Destructoras de Papel)',
            'Sillas', 'Sistemas de Alarma', 'Sistemas de Audio', 'Sistemas de Radio',
            'Sistemas de Video Conferencia', 'Telefonía', 'Televisores', 'Utensilios de Cocina'
        ];

        foreach ($categorias as $categoria) {
            DB::table('categorias')->insert([
                'nombre' => $categoria,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}