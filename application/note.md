duplicate value of NRK in master_pegawai :
121315
170593
175815
170855
176004
180655
0
-
2018
2017
.
1
3101011907930002
2019
80246090

## todo
1. add verified_by and verifed_at (done)
2. add notification when rejected, and add notification animation
3. add chat module
4. thumbs untuk foto profil di halaman monitoring, supaya load page lbh responsive (done)
5. pemeriksaan sewaktu2
6. remove photo if belum terima on verification page
7. show catatan by petugas on verification page (done)
8. show pesan admin bila ditolak (done)
9. fix input apd kasektor muncul di verif kasektor (done)
10. add data pegawai dan detail data pegawai di laporan kasektor
11. reward and punishment (ranking sektor berdasarkan kecepatan)
12. tambah kata NPJLP pada halaman login (done)
13. tambah kata ukuran.max.5MB (tidak perlu, sdh pakai compress js)
14. tambah whos online dengan tambah coloumn last_seen pada table user
15. otentifikasi menggunakan kode sektor karena ada beberapa kode_pos user yang 2.1 sedangkan kasektor 2.11
16. rubah penggunaan kode_pos jadi berdasarkan kode_pos_id (done)
17. tambah costum filter data pada admin sudin spt di bstatus/chart
18. buat landing page yang berisi informasi (literasi pantang pulang sblm padam, utamakan keselamatan, sambutan kadis, apd, cara mengukur apd)
19. pindahkan fungsi _get_users, dkk ke controller myAPd agar bisa diakses bersama
20. add photo langsung menggunakan kamera hp
21. set data apd to null when edit apd data to belum terima (done)


<li>
    <a class="item" data-toggle="collapse" href="#subMenu" role="button" aria-expanded="false" aria-controls="subMenu">
        <div class="icon-box bg-primary">
            <ion-icon name="warning"></ion-icon>
        </div>
        <div class="in">
            Laporan Sewaktu-waktu
        </div>
    </a>
    <div class="collapse" id="subMenu">
        <ul class="listview flush transparent no-line image-listview ml-2">
            <li>
                <a href="<?php echo base_url(); ?>petugas/lapor_sewaktu" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="infinite"></ion-icon>
                    </div>
                    <div class="in">
                        Lapor
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>petugas/progress_sewaktu" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="time"></ion-icon>
                    </div>
                    <div class="in">
                        Progress
                    </div>
                </a>
            </li>
        </ul>
    </div>
</li>