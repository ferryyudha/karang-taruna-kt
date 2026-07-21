@extends('public.layouts.app')
@section('title', 'Halaman Tidak Ditemukan — Karang Taruna')

@push('styles')
<style>
    .error-page {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 80px 0;
    }
    .error-code {
        font-size: clamp(5rem, 15vw, 9rem);
        font-weight: 900;
        line-height: 1;
        background: linear-gradient(135deg, #4154F1, #7C3AED);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-family: 'Poppins', sans-serif;
        margin-bottom: 16px;
    }
    .error-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 12px;
        font-family: 'Poppins', sans-serif;
    }
    .error-desc {
        color: #64748B;
        font-size: 1rem;
        line-height: 1.8;
        max-width: 420px;
        margin: 0 auto 32px;
    }
    .error-icon-bg {
        width: 80px; height: 80px;
        background: linear-gradient(135deg, #EFF6FF, #F5F3FF);
        border-radius: 24px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 24px;
        box-shadow: 0 4px 20px rgba(65,84,241,0.1);
    }
    .btn-home {
        background: linear-gradient(135deg, #4154F1, #7C3AED);
        color: white;
        padding: 14px 32px;
        border-radius: 14px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(65,84,241,0.3);
    }
    .btn-home:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(65,84,241,0.4);
        color: white;
    }
    .btn-back {
        color: #64748B;
        padding: 14px 24px;
        border-radius: 14px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #E2E8F0;
        transition: all 0.3s;
        background: white;
    }
    .btn-back:hover {
        background: #F8FAFC;
        color: #0F172A;
        border-color: #CBD5E1;
    }
</style>
@endpush

@section('content')
<div class="error-page">
    <div class="container text-center">
        <div class="error-icon-bg">
            <i class="bi bi-search" style="font-size:2rem; color:#4154F1;"></i>
        </div>
        <div class="error-code">404</div>
        <h1 class="error-title">Halaman Tidak Ditemukan</h1>
        <p class="error-desc">
            Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin sudah dipindahkan,
            dihapus, atau URL yang Anda masukkan salah.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ url('/') }}" class="btn-home">
                <i class="bi bi-house-fill"></i> Kembali ke Beranda
            </a>
            <a href="javascript:history.back()" class="btn-back">
                <i class="bi bi-arrow-left"></i> Halaman Sebelumnya
            </a>
        </div>
    </div>
</div>
@endsection
