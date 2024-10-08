<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LaporanTcpdf extends CI_Controller {
	function index()
	{
        $pdf = new \TCPDF();
        $pdf->AddPage('L', 'mm', 'A4');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(277, 10, "DAFTAR PEGAWAI AYONGODING.COM", 0, 1, 'C');
        $pdf->SetAutoPageBreak(true, 0);
        // Add Header
        $pdf->Ln(10);
        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(20, 8, "No", 1, 0, 'C');
        $pdf->Cell(100, 8, "Nama Pegawai", 1, 0, 'C');
        $pdf->Cell(120, 8, "Alamat", 1, 0, 'C');
        $pdf->Cell(37, 8, "Telp", 1, 1, 'C');
        $pdf->SetFont('', '', 12);
        $pegawai = $this->db->get('sys_cover')->result();
        $no=0;
        foreach ($pegawai as $data){
            $no++;
            $pdf->Cell(20,8,$no,1,0, 'C');
            $pdf->Cell(100,8,$data->uuid_cover,1,0);
            $pdf->Cell(120,8,$data->mapel,1,0);
            $pdf->Cell(37,8,$data->kelas,1,1);
        }
        $pdf->SetFont('', 'B', 10);
        $pdf->Cell(277, 10, "Laporan Pdf Menggunakan Tcpdf, Instalasi Tcpdf Via Composer", 0, 1, 'L');
        $pdf->Output('Laporan-Tcpdf-CodeIgniter.pdf'); 
	}
}
