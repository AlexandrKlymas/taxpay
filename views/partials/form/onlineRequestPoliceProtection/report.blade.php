<h3>#{{ $number }}</h3>
<h3>особисті дані</h3>
ПІБ: <b>{{ $full_name }}</b><br>
Телефон для звязку: <b>{{ $phone }}</b><br>
Email: <b>{{ $email }}</b><br>

<h3>ДАНІ ОБ'ЄКТА МОНТАЖУ</h3>
Послуга: <b>{{ $service_title }}</b><br>
Область: <b>{{ $region_title }}</b><br>
Район: <b>{{ $district }}</b><br>
Адрес: <b>{{ $address }}</b><br>
Поверх: <b>{{ $floor }}</b><br>
Кімнат: <b>{{ $rooms }}</b><br>
Домашні тварини:<b>{{ $pet?'Так': 'Ні' }}</b><br>
Охоронне обладнання: <b>{{ $secure? 'Так':'Ні' }}</b><br>