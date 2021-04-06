@extends('layouts.front.app')
@section('og')
<meta property="og:type" content="contato" />
<meta property="og:title" content="contato" />
<meta property="og:description" content="formulário de contatos" />
<link rel="canonical" href="http://www.easyshop.com.br/contato" />
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{@asset('css/contact.css')}}">
<link rel="stylesheet" type="text/css" href="{{@asset('css/contact_responsive.css')}}">
<link rel="stylesheet" type="text/css" href="{{@asset('css/style.min.css')}}">
@endsection
@section('content')
@include('layouts.front.contact');
@endsection
@section('js')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCIwF204lFZg1y4kPSIhKaHEXMLYxxuMhA"></script>
<script src="{{@asset('js/contact_custom.js')}}"></script>
@endsection