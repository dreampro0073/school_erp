<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }
        .center { text-align: center; }
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 12px; }
        .heading { font-weight: bold; margin-top: 10px; text-align: center; }
        .row { margin-bottom: 8px; }
        .line {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 300px;
            padding: 2px 5px;
        }
        .small { font-size: 10px; }
    </style>
</head>
<body>

<div class="center">
    @if(!empty($student['logo']))
        <img src="{{ $student['logo'] }}" style="width:60px; position:absolute; left:20px; top:20px;">
    @endif

    <div class="title">GYAN GANGA MATA PUBLIC SCHOOL</div>
    <div class="subtitle">Gali No. 3, Behind Mata Ki Murti, Vill. Kangri, Hardiawar</div>
    <div class="heading">APPLICATION FOR ADMISSION FORM</div>
</div>

<div class="row">
    S.R. No.: <span class="line">{{ $student['sr_no'] ?? '' }}</span>
    &nbsp;&nbsp;&nbsp;
    Admitted to Class: <span class="line">{{ $student['standard']['name'] ?? '' }}</span>
</div>

<div class="center small">(To be filled up by the parent or guardian of the student)</div>

<br>

<div class="row">
1. Date of Application:
<span class="line">{{ now()->format('d-m-Y') }}</span>
</div>

<div class="row">
2. Name & Address of parent or Guardian applying and relation:
<span class="line">
    {{ $student['father_name'] ?? '' }} (Father), {{ $student['residential_address'] ?? '' }}
</span>
</div>

<div class="row">
3. Name of Scholar:
<span class="line">{{ $student['name'] ?? '' }}</span>
</div>

<div class="row">
4. Father's Name and Occupation:
<span class="line">{{ $student['father_name'] ?? '' }}</span>
</div>

<div class="row">
5. Mother's Name and Occupation:
<span class="line">{{ $student['mother_name'] ?? '' }}</span>
</div>

<div class="row">6. Residence:</div>

<div class="row">
(1) Name of Scholar's Residence in Pradesh:
<span class="line">{{ $student['residential_address'] ?? '' }}</span>
</div>

<div class="row">
(2) Length of Scholar's Residence in Pradesh:
<span class="line"></span>
</div>

<div class="row">
7. Religion & Caste:
<span class="line">
    {{ $student['religion']['master_name'] ?? '' }} /
    {{ $student['cast']['master_name'] ?? '' }}
</span>
</div>

<div class="row">
8. Nationality:
<span class="line">Indian</span>
</div>

<div class="row">
9. Scholar's Date of Birth:
<span class="line">
    {{ !empty($student['dob']) ? \Carbon\Carbon::parse($student['dob'])->format('d-m-Y') : '' }}
</span>
</div>

<div class="row">
10. Age:
<span class="line">
    {{ !empty($student['dob']) ? \Carbon\Carbon::parse($student['dob'])->age : '' }}
</span>
</div>

<div class="row">
11. First Language:
<span class="line"></span>
</div>

<div class="row">
12. Class for which admission is sought:
<span class="line">{{ $student['standard']['name'] ?? '' }}</span>
</div>

<div class="row">
13. Last school attended:
<span class="line">{{ $student['previous_school'] ?? '' }}</span>
</div>

<br>

<div class="small">
I agree to abide by the rules of the department as laid down in the Education Code.
<br><br>

Note: Application for school living certificate must be signed in all cases.
<br><br>

* If the scholar was married before 18 years:
<br>
(e) was married before 18 years: ____________________________
<br>
(o) was not married (if inapplicable, cut out)
<br><br>

* Scholar was 18 years of age at the time of his marriage on: ____________________________
<br><br>

I hereby certify that <span class="line">{{ $student['name'] ?? '' }}</span>
<br>
has not previously attended any recognised institution.
<br><br>

(a) the date of birth of the Scholar was
<span class="line">
    {{ !empty($student['dob']) ? \Carbon\Carbon::parse($student['dob'])->format('d-m-Y') : '' }}
</span>
<br>

(b) the date of birth of the Scholar was herein stated is correct
<span class="line"></span>
</div>

<br><br>

<div class="small">
Note: No Change may be made in the age of entry of the scholar's records during his career in this school.
</div>

<br><br>

<div style="display:flex; justify-content: space-between;">
    <div>
        Date: <span class="line">{{ now()->format('d-m-Y') }}</span>
        <br> Signature of Parent/Guardian
    </div>
    <div>
        Signature of Parent/Guardian
    </div>
</div>

</body>
</html>