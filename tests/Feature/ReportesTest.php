<?php

namespace Tests\Feature;

use App\Services\Contracts\ReportesServiceInterface;
use Tests\TestCase;

class ReportesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Autenticación mock para las rutas protegidas
        session([
            'user' => [
                'id' => 1,
                'name' => 'Usuario Test',
                'email' => 'test@ganaderasoft.com',
                'token' => 'mock-jwt-token',
                'roles' => ['admin'],
            ],
            'selected_finca' => [
                'id' => 1,
                'nombre' => 'Finca Demo',
            ]
        ]);
    }

    public function test_reporte_general_view_renders_successfully()
    {
        $this->mock(ReportesServiceInterface::class, function ($mock) {
            $mock->shouldReceive('getReporteGeneral')->once()->andReturn([
                'success' => true,
                'data' => [
                    'finca' => ['id' => 1, 'nombre' => 'Finca Demo'],
                    'total_animales' => 1,
                    'animales' => [
                        [
                            'id' => 1,
                            'codigo' => 'VAC-001',
                            'nombre' => 'Mariposa',
                            'sexo' => 'H',
                            'categoria' => 'Vaca',
                            'estatus' => 'Activo',
                            'archivado' => false,
                            'rebano_nombre' => 'Lote 1',
                            'edad_meses' => 24,
                            'edad_formateada' => '2 años',
                            'raza' => 'Holstein',
                            'peso_ingreso' => 350.0,
                            'fecha_ingreso' => '2025-01-01',
                            'penultimo_peso' => 400.0,
                            'fecha_penultimo_peso' => '2025-06-01',
                            'ultimo_peso' => 450.0,
                            'fecha_ultimo_peso' => '2026-01-01',
                            'padre_codigo' => 'TORO-01',
                            'madre_codigo' => 'VAC-99',
                        ]
                    ]
                ]
            ]);
        });

        $response = $this->get('/reportes/general');

        $response->assertStatus(200);
        $response->assertSee('Reporte general de finca');
        $response->assertSee('VAC-001');
        $response->assertSee('Mariposa');
    }

    public function test_reporte_lactancias_view_renders_successfully()
    {
        $this->mock(ReportesServiceInterface::class, function ($mock) {
            $mock->shouldReceive('getReporteLactancias')->once()->andReturn([
                'success' => true,
                'data' => [
                    'finca' => ['id' => 1, 'nombre' => 'Finca Demo'],
                    'total_animales' => 1,
                    'produccion_total_finca' => 4500.5,
                    'animales' => [
                        [
                            'id' => 1,
                            'codigo' => 'VAC-001',
                            'nombre' => 'Mariposa',
                            'categoria' => 'Vaca',
                            'rebano_nombre' => 'Lote 1',
                            'total_lactancias' => 1,
                            'produccion_vitalicia' => 4500.5,
                            'lactancias' => [
                                [
                                    'id' => 1,
                                    'num_lactancia' => 1,
                                    'fecha_inicio' => '2025-01-01',
                                    'fecha_fin' => '2025-10-01',
                                    'estado' => 'Secada',
                                    'dias_lactancia' => 273,
                                    'p244' => 4000.0,
                                    'p270' => 4450.0,
                                    'p305' => 4500.5,
                                    'produccion_total' => 4500.5,
                                    'total_pesajes' => 10,
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        });

        $response = $this->get('/reportes/lactancias');

        $response->assertStatus(200);
        $response->assertSee('Historia de lactancias (Cálculo TIM)');
        $response->assertSee('VAC-001');
        $response->assertSee('4,500.50 Lts');
    }

    public function test_reporte_reproductivo_view_renders_successfully()
    {
        $this->mock(ReportesServiceInterface::class, function ($mock) {
            $mock->shouldReceive('getReporteReproductivo')->once()->andReturn([
                'success' => true,
                'data' => [
                    'finca' => ['id' => 1, 'nombre' => 'Finca Demo'],
                    'resumen' => [
                        'total_animales' => 1,
                        'total_eventos' => 2,
                        'total_partos' => 1,
                        'total_servicios' => 1,
                    ],
                    'animales' => [
                        [
                            'id' => 1,
                            'codigo' => 'VAC-001',
                            'nombre' => 'Mariposa',
                            'categoria' => 'Vaca',
                            'rebano_nombre' => 'Lote 1',
                            'total_eventos' => 2,
                            'eventos' => [
                                [
                                    'id' => 1,
                                    'origen' => 'Parto',
                                    'tipo' => 'Parto - Normal',
                                    'fecha' => '2025-01-01',
                                    'observacion' => 'Cría sana',
                                ],
                                [
                                    'id' => 2,
                                    'origen' => 'Servicio',
                                    'tipo' => 'Servicio - IA',
                                    'fecha' => '2025-04-01',
                                    'observacion' => 'Semen importado',
                                    'semen' => 'TORO-01',
                                    'tecnico' => 'Juan Pérez',
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        });

        $response = $this->get('/reportes/reproductivo');

        $response->assertStatus(200);
        $response->assertSee('Reporte reproductivo consolidado');
        $response->assertSee('Parto - Normal');
        $response->assertSee('Servicio - IA');
    }

    public function test_reporte_pesaje_leche_view_renders_successfully()
    {
        $this->mock(ReportesServiceInterface::class, function ($mock) {
            $mock->shouldReceive('getReportePesajeLeche')->once()->andReturn([
                'success' => true,
                'data' => [
                    'finca' => ['id' => 1, 'nombre' => 'Finca Demo'],
                    'resumen' => [
                        'total_pesajes' => 1,
                        'total_produccion' => 22.5,
                        'promedio_pesaje' => 22.5,
                    ],
                    'pesajes' => [
                        [
                            'id' => 1,
                            'codigo' => 'VAC-001',
                            'nombre' => 'Mariposa',
                            'categoria' => 'Lactancia',
                            'estatus' => 'Activo',
                            'lote' => 'Lote 1',
                            'fecha_evento' => '2026-08-01',
                            'lactancia_id' => 1,
                            'peso_total' => 22.5,
                        ]
                    ]
                ]
            ]);
        });

        $response = $this->get('/reportes/pesaje-leche');

        $response->assertStatus(200);
        $response->assertSee('Reporte de pesajes de leche');
        $response->assertSee('VAC-001');
        $response->assertSee('22.50');
    }

    public function test_reporte_rebanos_view_renders_successfully()
    {
        $this->mock(ReportesServiceInterface::class, function ($mock) {
            $mock->shouldReceive('getReporteRebanos')->once()->andReturn([
                'success' => true,
                'data' => [
                    'finca' => ['id' => 1, 'nombre' => 'Finca Demo'],
                    'total_rebanos' => 1,
                    'rebanos' => [
                        [
                            'id' => 1,
                            'nombre' => 'Lote 1',
                            'archivado' => false,
                            'created_at' => '2025-01-01 00:00:00',
                            'total_animales' => 25,
                            'animales_activos' => 24,
                            'animales_archivados' => 1,
                            'machos' => 2,
                            'hembras' => 23,
                        ]
                    ]
                ]
            ]);
        });

        $response = $this->get('/reportes/rebanos');

        $response->assertStatus(200);
        $response->assertSee('Reporte de rebaños y lotes');
        $response->assertSee('Lote 1');
        $response->assertSee('25');
    }
}
