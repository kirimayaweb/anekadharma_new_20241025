<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class KirimWa_GET_DATA_REQ_model extends CI_Model
{

    public $table = 'tbl_menu';
    public $table_gudang = 'trans_gudang';
    public $table_cover = 'sys_cover';
    public $nama_cover = 'nama_cover';
    public $id_gudang = 'id';
    public $tingkat_gudang = 'tingkat_produk';
    public $uuid_cover_produk = 'uuid_cover_produk';
    public $id = 'id_menu';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
        $this->load->model(array('User_model','Trans_penjualan_detail_model','KirimWa_model'));
        $this->load->helper(array('nominal'));
    }



	public function penjualan_sales_uuidmapel_uuidcover($uuid_sales_selected=null,$uuid_produk=null,$uuid_trans_penjualan_selected=null,$status_buku=null){
		$tahun_selected = $_SESSION['thn_selected'];
		$semester_selected = $_SESSION['semester_selected'];
		// $jumlah_halaman = 64;

		if($status_buku="buku_lama"){
			$sql = "SELECT 
			trans_penjualan_detail_a.exemplar_pesanan as exemplar_pesanan,
			trans_penjualan_detail_a.uuid_produk as uuid_produk,
			trans_penjualan_detail_a.uuid_trans_penjualan as uuid_trans_penjualan,
			-- trans_penjualan_detail_a.tahun_pesanan as tahun_pesanan,
	 
			sys_cover_a.nama_cover as nama_cover,
			sys_cover_a.nama_cover as nama_cover
	
			FROM trans_penjualan_detail trans_penjualan_detail_a 
	
		--     left join  tbl_produk tbl_produk_a ON tbl_produk_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
			left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = trans_penjualan_detail_a.uuid_cover_produk
		--    left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
	
			where trans_penjualan_detail_a.uuid_sales = '$uuid_sales'   AND  trans_penjualan_detail_a.uuid_produk = '$uuid_produk' AND  sys_cover_a.keterangan = '$status_buku'
			
			group by trans_penjualan_detail_a.uuid_cover_produk
			
			-- order by tbl_stok_barang_detail_a.tingkat_produk,tbl_stok_barang_detail_a.mapel_produk,tbl_stok_barang_detail_a.tahun_produk,tbl_stok_barang_detail_a.kelas_produk,tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.jumlah_halaman ASC
			";
	
		}else{


		}
		return $this->db->query($sql)->result();
	}

	public function penjualan_sales($uuid_sales_selected=null){

        $start=0;
        // $jumlah_kelompok=0;
        $x_tahun_session = $_SESSION['thn_selected'];
        $x_semester_session = $_SESSION['semester_selected'];

        $x_pesan_penjualan = "";
        $x_pesan_penjualanAkhir = "";

        // DATA KELOMPOK MAPEL
        $sql = "select tingkat from tbl_produk_mapel_referensi group by tingkat order by tingkat ASC";

            foreach ($this->db->query($sql)->result() as $m) {
                
                $this->db->where('tingkat_pesanan', $m->tingkat);
                $this->db->group_by('tingkat_pesanan');
                $this->db->group_by('jumlah_halaman_pesanan');
                $this->db->order_by("tingkat_pesanan", "ASC");
                $this->db->order_by("jumlah_halaman_pesanan", "ASC");
                $get_data_persales_per_tingkat = $this->db->get('trans_penjualan_detail'); 
                
                        // ====================  BARU  ==============================================

                        //LOOPING UNTUK TINGKAT ==> BARU ( SEMUA JMLH HALAMAN )
                        foreach ($get_data_persales_per_tingkat->result() as $get_data_persales_per_tingkat_list) { 
                                    
                                    $get_tingkat=$m->tingkat;
                                    $get_tingkat_baru=$m->tingkat . " BARU";
                                    $get_tingkat_baru_input_harga=$m->tingkat . "_BARU_HARGA";
                                    $get_tingkat_jmlh_halaman=$get_data_persales_per_tingkat_list->jumlah_halaman_pesanan;

                                    // print_r("<br/>");
                                    // print_r("<br/>");
                            ?>

                            <!-- BARU -->
                            <tr>
                                <td align="center"><?php  
                                    $get_urutan_kelompok=++$start;
                                    // echo $get_urutan_kelompok;  ?>
                                </td>
                                <td align="left">
                                    <?php 
                                        // echo $get_tingkat_baru;                                 
                                    ?> 
                                </td>
                                <td align="center">
                                    <!-- <?php //echo $get_tingkat_jmlh_halaman; ?> -->
                                </td>



                                <td align="right" style="color:red;">
                                    <?php 

                                // BARU // BARU  =========================================   64   ====================================
                      
                                $tingkat_BARU = $m->tingkat . " BARU";
                            
                                // print_r($tingkat_BARU);
                                // print_r("<br/>");
                                // print_r("<br/>");
                                // print_r("<br/>");

                                //PENJUALAN
                                $get_data_penjualan_by_sales_baru = $this->Trans_penjualan_detail_model->total_penjualan_by_uuid_sales_by_tingkat_BARU($uuid_sales_selected, $m->tingkat,$get_data_persales_per_tingkat_list->jumlah_halaman_pesanan);
                               
                                // print_r($get_data_penjualan_by_sales_baru);
                                // print_r("<br/>");
                                // print_r("<br/>");
                                // print_r("<br/>");


                                $get_data_penjualan_by_sales_baru_harga_satuan = $this->Trans_penjualan_detail_model->total_penjualan_by_uuid_sales_by_tingkat_BARU_harga_satuan($uuid_sales_selected, $m->tingkat,$get_data_persales_per_tingkat_list->jumlah_halaman_pesanan);
                               
                                // print_r($get_data_penjualan_by_sales_baru_harga_satuan);
                                // print_r("<br/>");
                                // print_r("<br/>");
                                // print_r("<br/>");
                                                                                                 
                                ?>
                                    <!-- <input type="text" class="form-control uang" name="jmlh_penjualan_<?php //echo $get_urutan_kelompok; ?>" id="jmlh_penjualan_<?php //echo $get_urutan_kelompok; ?>" placeholder="" value="<?php 
                                    // print_r($get_data_penjualan_by_sales_baru->penjualan); 
                                    if(!empty($get_data_penjualan_by_sales_baru->penjualan)){
                                        // print_r($get_data_penjualan_by_sales_baru->penjualan); 
                                    }else{
                                        // print_r("");
                                    }
                                    ?>" style="font-size:0.9vw;font-weight: bold" disabled />

                                </td>

                                 //harga baru -->
                                <td align="right">
                                   
                                    <!-- <input type="text" class="form-control uang" onkeyup="sum();" name="harga_penjualan_<?php echo $get_urutan_kelompok; ?>" id="harga_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="<?php 

                                    // if(!empty($get_data_penjualan_by_sales_baru->penjualan)){
                                    //     if(!empty($get_data_penjualan_by_sales_baru_harga_satuan->harga_jual_pesanan)){
                                    //         // print_r($get_data_penjualan_by_sales_baru_harga_satuan->harga_jual_pesanan); 
                                    //     }else{
                                    //         // print_r("");
                                    //     }
                                    // }
                                    ?>" style="font-size:0.9vw;font-weight: bold" />
                                </td>
                                
                                 TOTAL PENJUALAN BARU -->
                                <td align="right" style="color:red;">
                                    <!-- <input type="text" class="form-control uang" name="total_penjualan_<?php //echo $get_urutan_kelompok; ?>" id="total_penjualan_<?php //echo $get_urutan_kelompok; ?>" placeholder="" value="<?php  
                                    // if($get_data_penjualan_by_sales_baru->penjualan > 0 AND $get_data_penjualan_by_sales_baru_harga_satuan->harga_jual_pesanan > 0){
                                    //     // print_r($get_data_penjualan_by_sales_baru->penjualan*$get_data_penjualan_by_sales_baru_harga_satuan->harga_jual_pesanan); 
                                    // }else{
                                    //     // print_r("0");
                                    // }
                                    
                                    // PESAN WA
                                    // if($get_data_persales_per_tingkat_list AND $get_data_penjualan_by_sales_baru_harga_satuan ){
                                    //     $pesan_penjualan = $tingkat_BARU . " \r\n (" . $get_data_persales_per_tingkat_list->jumlah_halaman_pesanan . ") : \r\n" . nominal($get_data_penjualan_by_sales_baru->penjualan) . "\r\n"  . " X Rp. ". nominal($get_data_penjualan_by_sales_baru_harga_satuan->harga_jual_pesanan) . "\r\n" . " X Rp. ". nominal($get_data_penjualan_by_sales_baru->penjualan*$get_data_penjualan_by_sales_baru_harga_satuan->harga_jual_pesanan) . "\r\n" ;
                                    // }

                                    ?>" style="font-size:0.9vw;font-weight: bold; align:right"  />

                                </td>
                                
                            </tr>

                             PESAN WA BARU -->
                            <?php
                                if ($get_data_penjualan_by_sales_baru->penjualan >= 1) {
                                    $x_pesan_penjualan = $x_pesan_penjualan . "*" . $get_tingkat_baru . "*" . " \r\n ";
                                    $x_pesan_penjualan = $x_pesan_penjualan . "Hal *" . $get_tingkat_jmlh_halaman . "*" . " = ";
                                        $x_penjualan = $get_data_penjualan_by_sales_baru->penjualan;
                                    $x_pesan_penjualan = $x_pesan_penjualan . nominal($x_penjualan) . " exp. ";
                                    $total_per_mapel = $get_data_penjualan_by_sales_baru->penjualan*$get_data_penjualan_by_sales_baru_harga_satuan->harga_jual_pesanan;
                                    // $x_pesan_penjualan = $x_pesan_penjualan . " Rp." . nominal($get_data_penjualan_by_sales_baru_harga_satuan->harga_jual_pesanan) . " = Rp." . nominal($total_per_mapel) . " \r\n\r\n ";
                                    $x_pesan_penjualan = $x_pesan_penjualan .  " \r\n\r\n ";
                                }
                                // $x_pesan_penjualan = $x_pesan_penjualan . $pesan_penjualan;
                        }

                        // ====================  LAMA  ==============================================
                        //LOOPING UNTUK TINGKAT ==> LAMA ( SEMUA JMLH HALAMAN )
                        foreach ($get_data_persales_per_tingkat->result() as $get_data_persales_per_tingkat_list) {
                            $get_tingkat_jmlh_halaman=$get_data_persales_per_tingkat_list->jumlah_halaman_pesanan;        
                            $get_tingkat_lama=$m->tingkat . " LAMA";
                            $get_tingkat_lama_input_harga=$m->tingkat . "_LAMA_HARGA";
                                
                        ?>

                        <!-- LAMA -->
                        <tr>
                            <td align="center"><?php 
                                $get_urutan_kelompok = ++$start;
                                // echo $get_urutan_kelompok; 
                            ?></td>
                            <td align="left"><?php echo $get_tingkat_lama; ?> </td>
                            <td align="center"><?php echo $get_tingkat_jmlh_halaman; ?></td>        
                            <td align="right" style="color:red;">
                                <?php 
                                    
                                    $get_data_penjualan_by_sales_lama = $this->Trans_penjualan_detail_model->total_penjualan_by_uuid_sales_by_tingkat_LAMA($uuid_sales_selected, $m->tingkat,$get_data_persales_per_tingkat_list->jumlah_halaman_pesanan);

                                    $get_data_penjualan_by_sales_lama_harga_satuan = $this->Trans_penjualan_detail_model->total_penjualan_by_uuid_sales_by_tingkat_LAMA_harga_satuan($uuid_sales_selected, $m->tingkat,$get_data_persales_per_tingkat_list->jumlah_halaman_pesanan);
                                ?>
                                    
                                <!-- PENJUALAN EKSEMPLAR -->
                                    <input type="text" class="form-control uang" name="jmlh_penjualan_<?php echo $get_urutan_kelompok; ?>" id="jmlh_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="<?php if(!empty($get_data_penjualan_by_sales_lama->penjualan)){
                                        print_r($get_data_penjualan_by_sales_lama->penjualan); 
                                    }else{
                                        print_r("");
                                    } ?>" style="font-size:0.9vw;font-weight: bold" disabled />
                            </td>

                            <!-- Harga SATUAN lama -->
                            <td align="right">
                                <input type="text" class="form-control uang" onkeyup="sum();" name="harga_penjualan_<?php echo $get_urutan_kelompok; ?>" id="harga_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="<?php 
                                    if(!empty($get_data_penjualan_by_sales_lama->penjualan)){
                                        if(!empty($get_data_penjualan_by_sales_lama_harga_satuan->harga_jual_pesanan)){
                                            print_r($get_data_penjualan_by_sales_lama_harga_satuan->harga_jual_pesanan); 
                                        }else{
                                            print_r("");
                                        }
                                    } ?>" style="font-size:0.9vw;font-weight: bold" />
                            </td>
                            
                            <!-- Total Harga -->
                                    <!-- total_penjualan_lama_ -->
                            <td align="right" style="color:red;">
                                <?php //print_r($get_data_penjualan_by_sales_lama->penjualan*$get_harga_jual_lama); ?>
                                <input type="text" class="form-control uang" name="total_penjualan_<?php echo $get_urutan_kelompok; ?>" id="total_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="<?php 
                                if( !empty($get_data_penjualan_by_sales_lama->penjualan) AND !empty($get_data_penjualan_by_sales_lama_harga_satuan->harga_jual_pesanan)){
                                    print_r($get_data_penjualan_by_sales_lama->penjualan*$get_data_penjualan_by_sales_lama_harga_satuan->harga_jual_pesanan);
                                }else{
                                    print_r("0");
                                }
                                ; 
                                ?>" style="font-size:0.9vw;font-weight: bold" />
                            </td>
                            
                        </tr>

                        <!-- PESAN WA LAMA -->
                        <?php
                            if ($get_data_penjualan_by_sales_lama->penjualan >= 1) {
                                if ($get_data_penjualan_by_sales_lama->penjualan >= 1) {
                                    $x_pesan_penjualan = $x_pesan_penjualan . "*" . $get_tingkat_lama . "*" . " \r\n ";
                                    $x_pesan_penjualan = $x_pesan_penjualan . "Hal *" . $get_tingkat_jmlh_halaman . "*" . " = ";
                                        $x_penjualan = $get_data_penjualan_by_sales_lama->penjualan;
                                    $x_pesan_penjualan = $x_pesan_penjualan . nominal($x_penjualan) . " exp.";
                                        $total_per_mapel = $get_data_penjualan_by_sales_lama->penjualan*$get_data_penjualan_by_sales_lama_harga_satuan->harga_jual_pesanan;
                                    $x_pesan_penjualan = $x_pesan_penjualan ." \r\n\r\n ";
                                }
                            }
                            // $x_pesan_penjualan = $x_pesan_penjualan . $pesan_penjualan;

                        }

                
                        // $x_pesan_penjualanAkhir = $x_pesan_penjualan;

            }


            $nama_sales_selected = $this->KirimWa_GET_DATA_REQ_model->testdata($uuid_sales_selected);
            $tahun_selected = $_SESSION['thn_selected'];
            $tahun_selected_plus_1 = $_SESSION['thn_selected']+1;

            $semester_selected = $_SESSION['semester_selected'];
            $tgl_sekarang = date('Y-m-d H:i:s');

            if($semester_selected==1){
                $nama_semester="Ganjil";
            }else{
                $nama_semester="Genap";
            }


            // $pesan_wa = "*Laporan pengambilan lks* \r\n *Bpk/Ibu* *" . $nama_sales_selected . "* \r\n Di " . $alamat_sales_selected . " \r\n Semester " . $nama_semester . " " . $tahun_selected ."-". $tahun_selected_plus_1 . " \r\n per " . $tgl_sekarang . " \r\n\r\n " . $x_pesan_penjualan . "\r\n *Total =* *Rp. " . nominal($Total_nominal) . "* \r\n\r\n Retur =  \r\n " . $x_detail_retur . " \r\n *Total Tagihan=* *Rp. " . nominal($Total_nominal-$x_harga_retur) ."* \r\n\r\n\r\n Titipan dana = \r\n " . $x_detail_angsuran . " \r\n *Total Titipan dana=* *" . nominal($x_pembayaran_total) ."* \r\n\r\n\r\n *Sisa Tagihan =* *" . nominal($sisaTagihan) . "*";
            
            $pesan_wa = "*Laporan pengambilan lks* \r\n *Bpk/Ibu* *" . $nama_sales_selected . "* \r\n Di " . $alamat_sales_selected . " \r\n Semester " . $nama_semester . " " . $tahun_selected ."-". $tahun_selected_plus_1 . " \r\n per " . $tgl_sekarang . " \r\n\r\n " . $x_pesan_penjualan . "\r\n *Total =* *Rp. " . nominal($Total_nominal) . "* \r\n\r\n";




            // KIRIM KE WA
            $nomorhp="08157045860";
            $this->KirimWa_model->kirimwa($nomorhp, $pesan_wa);

    }

	public function testdata($uuid_sales=null){
		$sql = "SELECT 
		tbl_sales_a.uuid_sales as uuid_sales,
		tbl_sales_a.nama_sales as nama_sales

		FROM tbl_sales tbl_sales_a 

		where tbl_sales_a.uuid_sales = '$uuid_sales'
		";

		return $this->db->query($sql)->row()->nama_sales;
	}

    public function cekdata($uuid_sales_selected=null){
        
        $nama_sales = $this->KirimWa_GET_DATA_REQ_model->testdata($uuid_sales_selected);

        $get_tingkat_baru="MA";
        $get_tingkat_jmlh_halaman="64";

        $x_pesan_penjualan = "*" . $nama_sales . "*" . " \r\n ";
        $x_pesan_penjualan = $x_pesan_penjualan . "*" . $get_tingkat_baru . "*" . " \r\n ";
        $x_pesan_penjualan = $x_pesan_penjualan . "Hal *" . $get_tingkat_jmlh_halaman . "*" . " \r\n ";


        // print_r($x_pesan_penjualan);
        return $x_pesan_penjualan;
    }


    public function kirimwa_data_stock($nomorwa = null,  $kata2 = null,  $kata3 = null){

        // $pesantext = "Stock Kata 2: " . $kata2  . " Kata 3 = "  . $kata3 . " kata 4 = "  . $kata4  . " Kata 5 = "  . $kata5 . " kata 6 = "  . $kata6  . " Working Great!!! ";

        //PROSES AMBIL DATA
            // $kata2 ==> tingkat
            // $kata3 ==> mapel / cover
            if($kata2 AND $kata3){
                $pesantext = "ada kata 2 = " . $kata2 . " dan kata 3 = " . $kata3;

                // $sql = "select tingkat from tbl_produk_mapel_referensi group by tingkat order by tingkat ASC";

                //convert $kata3 ==> menjadi uuid_cover dari tabel ss_cover

                // $this->db->or_like('nama_cover', $kata3);
                // $this->db->from($this->table_cover);
                // print_r($this->db->result());
                // die;
     
                $this->db->where($this->nama_cover, $kata3);                
                $uuid_cover_dari_kata3=$this->db->get($this->table_cover)->row()->uuid_cover;

                // $this->db->where($this->tingkat_gudang, $kata2);
                // $this->db->where($this->uuid_cover_produk, $uuid_cover_dari_kata3);
                // // $this->db->order_by($this->id, $this->order);
                // $this->db->get($this->table_gudang)->result();

                // print_r($this->db->get($this->table_gudang)->result());
                // die;

                // $sql = "select tingkat from tbl_produk_mapel_referensi group by tingkat order by tingkat ASC";
                

                $sql = "SELECT 
                trans_gudang_a.uuid_gudang as uuid_gudang,
                trans_gudang_a.tingkat_produk as tingkat_produk,
                trans_gudang_a.uuid_produk as uuid_produk,
                trans_gudang_a.mapel_produk as mapel_produk,
                trans_gudang_a.jumlah_halaman as jumlah_halaman,
                trans_gudang_a.kelas_produk as kelas_produk,
                trans_gudang_a.tahun_produk as tahun_produk,
                trans_gudang_a.semester_produk as semester_produk,
                sum(trans_gudang_a.jumlah) as jumlah_ada_digudang
        
                FROM trans_gudang trans_gudang_a 
                
                left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = trans_gudang_a.uuid_produk

                where trans_gudang_a.tingkat_produk = '$kata2' AND  trans_gudang_a.uuid_cover_produk = '$uuid_cover_dari_kata3'

                group by trans_gudang_a.uuid_produk

                order by tbl_produk_mapel_referensi_a.id asc
                ";

                $x_data_gudang = "";
                $x_data_gudang = $x_data_gudang . "*Data Stock: " . $kata2 . "* " . $kata3 . ": \r\n ";

                foreach ($this->db->query($sql)->result() as $data_list) {
                    
                    
                    // $x_data_gudang = $x_data_gudang . "*" . $data_list->tingkat_produk . "*" . " \r\n ";
                    $x_data_gudang = $x_data_gudang . "*" . $data_list->mapel_produk . "*";
                    $x_data_gudang = $x_data_gudang . " _( Hlmn: " . $data_list->jumlah_halaman . " )_ " . " \r\n ";
                    $x_jumlah_exemplar_di_gudang = $data_list->jumlah_ada_digudang;
                    $x_data_gudang = $x_data_gudang . "*" . nominal($x_jumlah_exemplar_di_gudang) . "* exp. \r\n\r\n";
                   
                }
                
                // print_r($x_data_gudang);
                // die;

                $pesantext = $x_data_gudang;

               

            }elseif ($kata2){
                
                $sql = "SELECT 
                trans_gudang_a.uuid_gudang as uuid_gudang,
                trans_gudang_a.tingkat_produk as tingkat_produk,
                trans_gudang_a.uuid_produk as uuid_produk,
                trans_gudang_a.mapel_produk as mapel_produk,
                trans_gudang_a.jumlah_halaman as jumlah_halaman,
                trans_gudang_a.kelas_produk as kelas_produk,
                trans_gudang_a.tahun_produk as tahun_produk,
                trans_gudang_a.semester_produk as semester_produk,
                sum(trans_gudang_a.jumlah) as jumlah_ada_digudang
        
                FROM trans_gudang trans_gudang_a 
                
                left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = trans_gudang_a.uuid_produk

                where trans_gudang_a.tingkat_produk = '$kata2'

                group by trans_gudang_a.uuid_produk

                order by tbl_produk_mapel_referensi_a.id asc
                ";

                $x_data_gudang = "";
                $x_data_gudang = $x_data_gudang . "*Data Stock: " . $kata2 . ": \r\n ";

                foreach ($this->db->query($sql)->result() as $data_list) {
                    
                    
                    // $x_data_gudang = $x_data_gudang . "*" . $data_list->tingkat_produk . "*" . " \r\n ";
                    $x_data_gudang = $x_data_gudang . "*" . $data_list->mapel_produk . "*";
                    $x_data_gudang = $x_data_gudang . " _( Hlmn: " . $data_list->jumlah_halaman . " )_ " . " \r\n ";
                    $x_jumlah_exemplar_di_gudang = $data_list->jumlah_ada_digudang;
                    $x_data_gudang = $x_data_gudang . "*" . nominal($x_jumlah_exemplar_di_gudang) . "* exp. \r\n\r\n";
                   
                }
                
                // print_r($x_data_gudang);
                // die;

                $pesantext = $x_data_gudang;

               
            }else{
                $pesantext = "Belum ada definisi [tingkat] dan [cover] , stock [tingkat] [cover] ";
            }

            // if($kata3){
            //     $pesantext = "ada kata 2=" . $kata2 . " & kata3 = " . $kata3;
            // }else{
            //     $pesantext = "TIDAK ada kata 3=";
            // }

                

        // PROSES KIRIM WA
        if (!($nomorwa)) {
            $nomorwa = "628157045860";
        }
        if (!($pesantext)) {
            $pesantext = "ada akses masuk";
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.fonnte.com/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array(
        // 'target' => '08123456789|Fonnte|Admin,08123456789|Lili|User',
        'target' => $nomorwa,
        'message' => $pesantext,
        
        ),
          CURLOPT_HTTPHEADER => array(
            'Authorization: 1BFYxDnYcsZjm9nahfEG'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        // echo $response;

    }


    public function kirimwa($pesantext = null){


        // $pesantext = $pesantext . " Working Great!!! ";

        if (!($nomorwa)) {
            $nomorwa = "628157045860";
        }
        if (!($pesantext)) {
            $pesantext = "ada akses masuk";
        }


        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.fonnte.com/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array(
        // 'target' => '08123456789|Fonnte|Admin,08123456789|Lili|User',
        'target' => $nomorwa,
        'message' => $pesantext,
        
        ),
          CURLOPT_HTTPHEADER => array(
            'Authorization: 1BFYxDnYcsZjm9nahfEG'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        // echo $response;

    }


}

/* End of file Menu_model.php */
/* Location: ./application/models/Menu_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-10-04 10:50:27 */
/* http://harviacode.com */