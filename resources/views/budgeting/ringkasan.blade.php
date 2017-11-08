@extends('budgeting.layout')

@section('content')
<div id="content" class="app-content" role="main">
  <div class="hbox hbox-auto-xs hbox-auto-sm ng-scope">
    <div class="col">
      <div class="app-content-body ">

        <div class="bg-light lter">    
          <ul class="breadcrumb bg-white m-b-none">
            <li><a href="#" class="btn no-shadow" ui-toggle-class="app-aside-folded" target=".app">
              <i class="icon-bdg_expand1 text"></i>
              <i class="icon-bdg_expand2 text-active"></i>
            </a>   </li>
            <li><a href= "{{ url('/') }}">Dashboard</a></li>
            <li class="active"><i class="fa fa-angle-right"></i>Ringkasan</li>                                
          </ul>
        </div>

        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Ringkasan {{$info}}</h5>
                </div>               
                <div class="tab-content tab-content-alt-1 bg-white">
                  <div role="tabpanel" class="active tab-pane" id="tab-1">
                    <div id="table-ringkasan">
                      <table class="table">
                        <thead>
                          <tr>
                            <th width="1%">Kode</th>
                            <th>Uraian</th>
                            <th width="20%" class="text-right">RAPBD 2018</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><b>1</b></td>
                            <td><b>PENDAPATAN</b></td>
                            <td class="text-right"><b> {{ number_format($pd,0,',','.') }}</b></td>
                          </tr>
                          <tr>  
                            <td><b>1.1</b></td>
                            <td><b>PENDAPATAN ASLI DAERAH</b></td>
                            <td class="text-right"><b> {{ number_format($pd1,0,',','.') }}</b></td>
                          </tr>
                          <tr>  
                            <td>1.1.1</td>
                            <td>Pajak Daerah</td>
                            <td class="text-right"> {{ number_format($pd11,0,',','.') }}</td>
                          </tr>                              
                          <tr>  
                            <td>1.1.2</td>
                            <td>Retribusi Daerah</td>
                            <td class="text-right"> {{ number_format($pd12,0,',','.') }}</td>
                          </tr>                              
                          <tr>  
                            <td>1.1.3</td>
                            <td>Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan </td>
                            <td class="text-right"> {{ number_format($pd13,0,',','.') }}</td>
                          </tr>                              
                          <tr>  
                            <td>1.1.4</td>
                            <td>Lain-lain Pendapatan Asli Daerah yang Sah </td>
                            <td class="text-right"> {{ number_format($pd14,0,',','.') }}</td>
                          </tr>                              
                          <tr>  
                            <td><b>1.2</b></td>
                            <td><b>DANA PERIMBANGAN</b></td>
                            <td class="text-right"><b> {{ number_format($pd2,0,',','.') }}</b></td>
                          </tr>
                          <tr>  
                            <td>1.2.1</td>
                            <td>Bagi Hasil Pajak/Bagi Hasil Bukan Pajak </td>
                            <td class="text-right"> {{ number_format($pd21,0,',','.') }}</td>
                          </tr>                              
                          <tr>  
                            <td>1.2.2</td>
                            <td>Dana Alokasi Umum</td>
                            <td class="text-right"> {{ number_format($pd22,0,',','.') }}</td>
                          </tr>                              
                          <tr>  
                            <td>1.2.3</td>
                            <td>Dana Alokasi Khusus</td>
                            <td class="text-right"> {{ number_format($pd23,0,',','.') }}</td>
                          </tr>                              
                          <tr>  
                            <td><b>1.3</b></td>
                            <td><b>LAIN-LAIN PENDAPATAN DAERAH YANG SAH</b></td>
                            <td class="text-right"><b> {{ number_format($pd3,0,',','.') }}</b></td>
                          </tr>
                          <tr>  
                            <td>1.3.1</td>
                            <td>Pendapatan Hibah</td>
                            <td class="text-right"> {{ number_format($pd31,0,',','.') }}</td>
                          </tr>                              
                          <tr>  
                            <td>1.3.2</td>
                            <td>Dana Bagi Hasil Pajak dari Provinsi dan Pemerintah Daerah Lainnya </td>
                            <td class="text-right"> {{ number_format($pd32,0,',','.') }}</td>
                          </tr>                              
                          <tr>  
                            <td>1.3.3</td>
                            <td>Dana Penyesuaian dan Otonomi Khusus </td>
                            <td class="text-right"> {{ number_format($pd33,0,',','.') }}</td>
                          </tr>                              
                          <tr>
                            <td>1.3.4</td>
                            <td>Bantuan Keuangan dari Provinsi atau Pemerintah Daerah Lainnya</td>
                            <td class="text-right"> {{ number_format($pd34,0,',','.') }}</td>
                          </tr>
                          <tr>
                            <td colspan="3"></td>
                          </tr>
                          <tr>
                            <td><b>2</b></td>
                            <td><b>BELANJA</b></td>
                            <td class="text-right"><b> {{ number_format($blv+($btl1+$btl3+$btl4+$btl7+$btl8),0,',','.') }}</b></td>
                          </tr>                              
                          <tr>
                            <td><b>2.1</b></td>
                            <td><b>BELANJA TIDAK LANGSUNG</b></td>
                            <td class="text-right"><b> {{ number_format($btl1+$btl3+$btl4+$btl7+$btl8,0,',','.') }}</b></td>
                          </tr>    
                          <tr>
                            <td>2.1.1</td>
                            <td>Belanja Pegawai</td>
                            <td class="text-right"> {{ number_format($btl1,0,',','.') }}</td>
                          </tr>                                                        
                          <tr>
                            <td>2.1.3</td>
                            <td>Belanja Subsidi</td>
                            <td class="text-right"> {{ number_format($btl3,0,',','.') }}</td>
                          </tr>                                                        
                          <tr>
                            <td>2.1.4</td>
                            <td>Belanja Hibah</td>
                            <td class="text-right"> {{ number_format($btl4,0,',','.') }}</td>
                          </tr>                                                        
                          <tr>
                            <td>2.1.7</td>
                            <td>Belanja Bantuan Keuangan Kepada Provinsi/kabupaten/kota Dan Pemerintahan Desa</td>
                            <td class="text-right"> {{ number_format($btl7,0,',','.') }}</td>
                          </tr>                                                        
                          <tr>
                            <td>2.1.8</td>
                            <td>Belanja Tidak Terduga </td>
                            <td class="text-right"> {{ number_format($btl8,0,',','.') }}</td>
                          </tr>                                                        
                          <tr>
                            <td><b>2.2</b></td>
                            <td><b>BELANJA LANGSUNG</b></td>
                            <td class="text-right"><b> {{ number_format($blv,0,',','.') }}</b></td>
                          </tr>
                          <tr>
                            <td>2.2.1</td>
                            <td>Belanja Pegawai </td>
                            <td class="text-right"> {{ number_format($b1v,0,',','.') }}</td>
                          </tr>                                                            
                          <tr>
                            <td>2.2.2</td>
                            <td>Belanja Barang dan jasa </td>
                            <td class="text-right"> {{ number_format($b2v,0,',','.') }}</td>
                          </tr>                                                            
                          <tr>
                            <td>2.2.3</td>
                            <td>Belanja Modal </td>
                            <td class="text-right"> {{ number_format($b3v,0,',','.') }}</td>
                          </tr>
                          <tr>
                            <td colspan="2" class="text-right"><b>SURPLUS / (DEFISIT) </b></td>
                            <td class="text-right"><b> ({{ number_format(($blv+$btl1+$btl3+$btl4+$btl7+$btl8)-$pd,0,',','.') }})</b></td>
                          </tr>   
                          <tr>
                            <td><b>3</b></td>
                            <td><b>PEMBIAYAAN DAERAH</b></td>
                            <td class="text-right"><b> {{ number_format($penerimaan-$pengeluaran,0,',','.') }}</b></td>
                          </tr>                                                                                     
                          <tr>
                            <td><b>3.1</b></td>
                            <td><b>PENERIMAAN PEMBIAYAAN DAERAH</b></td>
                            <td class="text-right"><b> {{ number_format($penerimaan,0,',','.') }}</b></td>
                          </tr>   
                           
                          <!-- penerimaan --> 
                          <tr>
                            <td>3.1.1</td>
                            <td>Sisa Lebih Perhitungan Anggaran Tahun Anggaran Sebelumnya</td>
                            <td class="text-right"> <?php echo number_format($pen611,0,',','.') ?></td>
                          </tr>
                          <tr>
                            <td>3.1.2</td>
                            <td>Pencairan Dana Cadangan </td>
                            <td class="text-right"> <?php echo number_format($pen612,0,',','.') ?></td>
                          </tr>
                          <tr>
                            <td>3.1.3</td>
                            <td> Hasil Penjualan Kekayaan Daerah yang dipisahkan </td>
                            <td class="text-right"> <?php echo number_format($pen613,0,',','.') ?></td>
                          </tr>
                          <tr>
                            <td>3.1.4</td>
                            <td> Penerimaan kembali Pemberian Pinjaman </td>
                            <td class="text-right"> <?php echo number_format($pen615,0,',','.') ?></td>
                          </tr>
                           <tr>
                            <td>3.1.5</td>
                            <td> Penerimaan Pinjaman Daerah </td>
                            <td class="text-right"> <?php echo number_format($pen614,0,',','.') ?></td>
                          </tr>
                          

                          <tr>
                            <td colspan="2" class="text-right"><b>JUMLAH PENERIMAAN PEMBIAYAAN </b></td>
                            <td class="text-right"><b> {{ number_format($penerimaan,0,',','.') }}</b></td>
                          </tr>

                          <tr>
                            <td><b>3.2</b></td>
                            <td><b>PENGELUARAN PEMBIAYAAN DAERAH</b></td>
                            <td class="text-right"><b> {{ number_format($pengeluaran,0,',','.') }}</b></td>
                          </tr>   
                          
                          <!-- pengeluaran -->
                           <tr>
                            <td>3.2.1</td>
                            <td> Pembentukan Dana Cadangan </td>
                            <td class="text-right"> <?php echo number_format($peng621,0,',','.') ?></td>
                          </tr>
                           <tr>
                            <td>3.2.2</td>
                            <td> Penyertaan Modal (Investasi) Pemerintah Daerah </td>
                            <td class="text-right"> <?php echo number_format($peng622,0,',','.') ?></td>
                          </tr>
                           <tr>
                            <td>3.2.3</td>
                            <td> Pembayaran Pokok Utang </td>
                            <td class="text-right"> <?php echo number_format($peng623,0,',','.') ?></td>
                          </tr>



                          <tr>
                            <td colspan="2" class="text-right"><b>JUMLAH PENGELUARAN PEMBIAYAAN </b></td>
                            <td class="text-right"><b> {{ number_format($pengeluaran,0,',','.') }}</b></td>
                          </tr>
                          <tr>
                            <td colspan="2" class="text-right"><b>PEMBIYAAN NETTO</b></td>
                            <td class="text-right"><b> {{ number_format($penerimaan-$pengeluaran,0,',','.') }}</b></td>
                          </tr>                                                                                  
                          <tr>
                            <td colspan="4"></td>
                          </tr>
                          <tr>
                            <td colspan="2" class="text-right"><b>SISA LEBIH PEMBIAYAAN ANGGARAN TAHUN BERKENAAN</b></td>
                            @if(($blv+$btl1+$btl3+$btl4+$btl7+$btl8+$pengeluaran)-$pd-$penerimaan < 0)
                            <td class="text-right"><b> {{ number_format($pd+$penerimaan-($blv+$btl1+$btl3+$btl4+$btl7+$btl8+$pengeluaran),0,',','.') }} </b></td>
                            @else
                            <td class="text-right"><b> ({{ number_format(
                            ($blv+$btl1+$btl3+$btl4+$btl7+$btl8+$pengeluaran)-$pd-$penerimaan
                            ,0,',','.') }}) </b></td>
                            @endif
                            
                          </tr>  
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection

  @section('plugin')
  <script type="text/javascript">
  </script>
  @endsection