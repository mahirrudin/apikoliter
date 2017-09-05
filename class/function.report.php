<?php

/* function untuk kasih saran penggunaan hardware server
1. ambil persentase penggunaan CPU, RAM, dan HDD
2. kalkulasi persentase, dan hasilkan saram
- jika penggunaan kurang dari 50 % maka aman
- Jika penggunaan melebihi 50 % maka berikan hati - hati
- Jika penggunaan melebihi 80 % maka berikan berbahaya

*/

function advices($cpu, $ram, $hdd){

if($cpu <= 50){
	$cpu_cond = 'save';
}elseif($cpu >= 50 && $cpu <= 80){
	$cpu_cond = 'warning';
}else{
	$cpu_cond = 'danger';
}

if($ram <= 50){
	$ram_cond = 'save';
}elseif($ram >= 50 && $ram <= 80){
	$ram_cond = 'warning';
}else{
	$ram_cond = 'danger';
}

if($hdd <= 50){
	$hdd_cond = 'save';
}elseif($hdd >= 50 && $hdd <= 80){
	$hdd_cond = 'warning';
}else{
	$hdd_cond = 'danger';
}

	switch($cpu_cond)
    {
    	case 'save' 
    	:$sarancpu = '<span class="label label-success"><i class="fa fa-check"></i></span> Penggunaan CPU aman, karena masih dibawah 50%';
        break;

        case 'warning' 
    	:$sarancpu = '<span class="label label-warning"><i class="fa fa-exclamation-triangle"></i></span> Penggunaan CPU perlu diperhatikan, karena sudah lebih dari 50%';
        break;

        case 'danger' 
    	:$sarancpu = '<span class="label label-danger"><i class="fa fa-exclamation-triangle"></i></span> Penggunaan CPU sangat tinggi dan berbahaya, karena masih sudah lebih dari 80%. Lakukan pemeriksaan pada hardware dan software';
        break;
    }

    switch($ram_cond)
    {
    	case 'save' 
    	:$saranram = '<span class="label label-success"><i class="fa fa-check"></i></span> Penggunaan Memory aman, karena masih dibawah 50%';
        break;

        case 'warning' 
    	:$saranram = '<span class="label label-warning"><i class="fa fa-exclamation-triangle"></i></span> Penggunaan Memory perlu diperhatikan, karena sudah lebih dari 50%';
        break;

        case 'danger' 
    	:$saranram = '<span class="label label-danger"><i class="fa fa-exclamation-triangle"></span> Penggunaan Memory sangat tinggi dan berbahaya, karena sudah lebih dari 80%. Lakukan pemeriksaan terkait services yang terinstall, atau lakukan penambahan Memory';
        break;
    }

    switch($hdd_cond)
    {
    	case 'save' 
    	:$saranhdd = '<span class="label label-success"><i class="fa fa-check"></i></span> Penggunaan Hardisk aman, karena masih dibawah 50%';
        break;

        case 'warning' 
    	:$saranhdd = '<span class="label label-warning"><i class="fa fa-exclamation-triangle"></i></span> Penggunaan Hardisk perlu diperhatikan, karena sudah lebih dari 50%';
        break;

        case 'danger' 
    	:$saranhdd = '<span class="label label-danger"><i class="fa fa-exclamation-triangle"></i></span> Penggunaan Hardisk sangat tinggi dan berbahaya, karena sudah lebih dari 80%. Lakukan pemeriksaan pada direktori server, gunakan perintah "du -a / | sort -n -r | head -n 5" untuk melihat 5 direktori dengan ukuran terbesar dan segera bersihkan atau lakukan penambahan Hardisk';
        break;
    }

$saran = "<li>1. ".$sarancpu."</li><li>2. ".$saranram."</li><li>3. ".$saranhdd."</li>";
echo $saran;

}
/* function untuk konversi hari ke indonesia
1. buat case hari dalam indonesia
2. return nama harinya deh
*/

function dayid($dayname)
{
    switch($dayname)
    {
        case 'Monday':$hari="Senin";break; 
        case 'Tuesday':$hari="Selasa";break; 
        case 'Wednesday':$hari="Rabu";break; 
        case 'Thursday':$hari="Kamis";break; 
        case 'Friday':$hari="Jum'at";break; 
        case 'Saturday':$hari="Sabtu";break; 
        case 'Sunday':$hari="Minggu";break; 
    }

    return $hari;
}

?>