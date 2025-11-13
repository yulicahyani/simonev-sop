<?php

namespace App\Services;

use Illuminate\Support\Facades\Request;

class MenuService
{
    /**
     * Create a new class instance.
     */
    public static function getNavBarMenuWithCategory(){
        return [
            [
                'menu'  => [
                    [
                        'text'      => 'Dashboard',
                        'id'        => 'dashboard',
                        'url'       => route('backend.beranda'),
                        'icon'      => 'tachometer-alt',
                        'can'       => 'beranda',
                        'active'    => ['backend/beranda', 'backend/beranda/*']
                    ],
                ],
            ],
            [
                'cat_text'  => 'Pengaturan',
                'cat_id'    => 'pengaturan',
                'can'       => 'pengaturan',
                'active'    => ['backend/pengaturan', 'backend/pengaturan/*'],
                'menu'      => [
                    [
                        'text'      => 'Pengguna',
                        'id'        => 'users',
                        'icon'      => 'people',
                        'can'       => 'pengguna',
                        'desc'      => 'Menambah, mengubah dan menghapus pengguna',
                        'url'       => route('user.index'),
                        'active'    => ['backend/pengaturan/manajemen-pengguna/user', 'backend/manajemen-pengguna/user/*']
                    ],
                    [
                        'text'      => 'Hak Akses',
                        'id'        => 'permission',
                        'icon'      => 'key',
                        'can'       => 'hak-akses',
                        'desc'      => 'Menambah, mengubah dan menghapus hak akses',
                        'url'       => route('permission.index'),
                        'active'    => ['backend/pengaturan/manajemen-pengguna/permission', 'backend/manajemen-pengguna/permission/*']
                    ],
                    [
                        'text'      => 'Hak Akses Pengguna',
                        'id'        => 'user-permission',
                        'icon'      => 'shield-lock',
                        'can'       => 'hak-akses-pengguna',
                        'desc'      => 'Menambah, mengubah dan menghapus hak akses untuk role pengguna',
                        'url'       => route('user-permission.index'),
                        'active'    => ['backend/pengaturan/manajemen-pengguna/user-permission', 'backend/pengaturan/manajemen-pengguna/user-permission/*']
                    ],
                ]
            ],
            [
                'cat_text'  => 'Konsultasi SOP',
                'cat_id'    => 'konsultasi-sop',
                'can'       => 'konsultasi-sop',
                'active'    => ['backend/konsultasi-sop', 'backend/konsultasi-sop/*'],
                'menu'      => [
                    [
                        'text'      => 'Konsultasi SOP (Online)',
                        'id'        => 'konsultasi-sop-online',
                        'icon'      => 'chat-dots',
                        'can'       => 'konsultasi-sop-online',
                        'desc'      => 'Konsultasi/pendampingan penyusunan atau perbaikan SOP secara online melalui sistem.',
                        'url'       => route('konsultasi-sop-online.index'),
                        'active'    => ['backend/konsultasi-sop/konsultasi-sop-online', 'backend/konsultasi-sop/konsultasi-sop-online/*']
                    ],
                    [
                        'cat_text'  => 'Konsultasi SOP (Offline)',
                        'cat_id'    => 'konsultasi-sop-offline',
                        'id'        => 'konsultasi-sop-offline',
                        'can'       => 'konsultasi-sop-offline',
                        'desc'      => 'Jadwal konsultasi SOP dan buku daftar hadir konsultasi SOP',
                        'icon'      => 'person-video3',
                        'active'    => ['backend/konsultasi-sop/konsultasi-sop-offline', 'backend/konsultasi-sop/konsultasi-sop-offline/*'],
                        'menu'  => [
                            [
                                'text'      => 'Jadwal Konsultasi SOP',
                                'id'        => 'jadwal-konsultasi-sop',
                                'url'       => route('konsultasi-sop-offline.jadwal.index'),
                                'desc'      => 'Menambah, mengubah dan menghapus jadwal konsulltasi/pendampingan penyusunan SOP (Offline)',
                                'icon'      => 'calendar-range',
                                'can'       => 'jadwal-konsultasi-sop',
                                'active'    => ['backend/konsultasi-sop/konsultasi-sop-offline/jadwal', 'backend/konsultasi-sop/konsultasi-sop-offline/jadwal/*']
                            ],
                            [
                                'text'      => 'Buku Tamu Konsultasi SOP',
                                'id'        => 'buku-tamu-konsultasi-sop',
                                'url'       => route('konsultasi-sop-offline.buku-tamu.index'),
                                'desc'      => 'Buku daftar tamu konsulltasi/pendampingan penyusunan SOP (Offline)',
                                'icon'      => 'journal-bookmark-fill',
                                'can'       => 'buku-tamu-konsultasi-sop',
                                'active'    => ['backend/konsultasi-sop/konsultasi-sop-offline/buku-tamu', 'backend/konsultasi-sop/konsultasi-sop-offline/buku-tamu/*']
                            ],
                        ]
                    ],
                ]
            ],
            [
                'cat_text'  => 'Monev SOP',
                'cat_id'    => 'monev-sop',
                'can'       => 'monev-sop',
                'active'    => ['backend/monev-sop', 'backend/monev-sop/*'],
                'menu'  => [
                    [
                        'text'      => 'Monev',
                        'id'        => 'form-monev-sop',
                        'url'       => route('monev-sop.index'),
                        'desc'      => 'Mengisi form monitoring dan evaluasi SOP',
                        'icon'      => 'clipboard-data',
                        'can'       => 'form-monev-sop',
                        'active'    => ['backend/monev-sop/monev', 'backend/monev-sop/monev/*']
                    ],
                    [
                        'text'      => 'Periode Monev',
                        'id'        => 'periode-monev',
                        'url'       => route('monev-periode.index'),
                        'desc'      => 'Menambah, mengubah dan menghapus periode monitoring dan evaluasi SOP',
                        'icon'      => 'calendar-range',
                        'can'       => 'periode-monev-sop',
                        'active'    => ['backend/monev-sop/periode', 'backend/monev-sop/periode/*']
                    ],
                    [
                        'cat_text'  => 'Instrumen Monev',
                        'cat_id'    => 'instrumen-form-monev',
                        'id'        => 'instrumen-form-monev',
                        'can'       => 'instrumen-form-monev',
                        'desc'      => 'Menambah, mengubah dan menghapus instrumen formulir monitoring dan evaluasi SOP',
                        'icon'      => 'card-checklist',
                        'active'    => ['backend/monev-sop/instrumen-monev', 'backend/monev-sop/instrumen-monev/*'],
                        'menu'  => [
                            [
                                'text'      => 'Isntrumen Form 01',
                                'id'        => 'instrumen-form-01',
                                'url'       => route('instrumen-form1.index'),
                                'desc'      => 'Menambah, mengubah dan menghapus instrumen F01 monitoring dan evaluasi SOP',
                                'icon'      => 'file-earmark-text',
                                'can'       => 'instrumen-form-01',
                                'active'    => ['backend/monev-sop/instrumen-monev/instrumen-form1', 'backend/monev-sop/instrumen-monev/instrumen-form1/*']
                            ],
                            [
                                'text'      => 'Isntrumen Form 02',
                                'id'        => 'instrumen-form-02',
                                'url'       => route('instrumen-form2.index'),
                                'desc'      => 'Menambah, mengubah dan menghapus instrumen F02 monitoring dan evaluasi SOP',
                                'icon'      => 'file-earmark-text',
                                'can'       => 'instrumen-form-02',
                                'active'    => ['backend/monev-sop/instrumen-monev/instrumen-form2', 'backend/monev-sop/instrumen-monev/instrumen-form2/*']
                            ],
                        ]
                    ],
                ],
            ],
        ];
    }

    public static function isActive($segments){
		foreach ($segments as $segment) {
			if (Request::is($segment)) {
				return true;
			}
		}
		return false;
	}

}