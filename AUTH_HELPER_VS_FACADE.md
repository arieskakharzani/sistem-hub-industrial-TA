# 🔍 **Perbedaan `auth()->user()` vs `Auth::user()`**

## 📋 **Overview**

Kedua sintaks ini melakukan hal yang sama - mendapatkan user yang sedang login, tetapi ada perbedaan dalam cara implementasi dan dukungan IDE.

## 🔧 **Sintaks 1: `auth()->user()`**

```php
$user = auth()->user();
```

### **Karakteristik:**

-   **Helper Function**: `auth()` adalah helper function Laravel
-   **Dynamic Resolution**: Laravel secara dinamis memanggil `Auth` facade
-   **IDE Issues**: IDE sering tidak bisa melacak tipe return dengan baik
-   **Performance**: Sedikit overhead karena dynamic resolution
-   **Laravel Version**: Tersedia di semua versi Laravel

### **Contoh Penggunaan:**

```php
// Basic usage
$user = auth()->user();

// With checks
if (auth()->check()) {
    $user = auth()->user();
}

// In blade templates
@if(auth()->check())
    Hello, {{ auth()->user()->name }}
@endif
```

## 🔧 **Sintaks 2: `Auth::user()`**

```php
use Illuminate\Support\Facades\Auth;
$user = Auth::user();
```

### **Karakteristik:**

-   **Static Facade Call**: Langsung memanggil `Auth` facade
-   **Better IDE Support**: IDE dapat melacak tipe return dengan lebih baik
-   **Type Hinting**: Lebih mudah untuk IDE memberikan autocomplete
-   **Performance**: Sedikit lebih cepat karena static call
-   **Explicit Import**: Perlu import facade secara eksplisit

### **Contoh Penggunaan:**

```php
use Illuminate\Support\Facades\Auth;

// Basic usage
$user = Auth::user();

// With checks
if (Auth::check()) {
    $user = Auth::user();
}

// In blade templates
@if(Auth::check())
    Hello, {{ Auth::user()->name }}
@endif
```

## 📊 **Perbandingan Detail**

| Aspek             | `auth()->user()`        | `Auth::user()`         |
| ----------------- | ----------------------- | ---------------------- |
| **Type Safety**   | ⚠️ Kurang baik          | ✅ Lebih baik          |
| **IDE Support**   | ⚠️ Terbatas             | ✅ Lebih baik          |
| **Performance**   | ⚠️ Sedikit lebih lambat | ✅ Sedikit lebih cepat |
| **Readability**   | ✅ Lebih ringkas        | ⚠️ Perlu import        |
| **Laravel Style** | ✅ Laravel way          | ✅ Standard PHP        |

## 🎯 **Rekomendasi**

### **Gunakan `Auth::user()` untuk:**

-   **Controller Methods**: Lebih baik untuk type safety
-   **Complex Logic**: Ketika ada banyak operasi dengan user
-   **IDE Support**: Ketika ingin autocomplete yang lebih baik
-   **Performance**: Untuk aplikasi yang membutuhkan performa optimal

### **Gunakan `auth()->user()` untuk:**

-   **Blade Templates**: Lebih ringkas dan mudah dibaca
-   **Simple Checks**: Untuk pengecekan sederhana
-   **Quick Access**: Ketika hanya perlu akses cepat

## 🔧 **Implementasi di Controller**

### **Sebelum (dengan `auth()->user()`):**

```php
public function show($id)
{
    $anjuran = Anjuran::findOrFail($id);
    $user = auth()->user(); // ❌ IDE warning

    if ($user->active_role === 'mediator') {
        // ...
    }
}
```

### **Sesudah (dengan `Auth::user()`):**

```php
use Illuminate\Support\Facades\Auth;

public function show($id)
{
    $anjuran = Anjuran::findOrFail($id);
    $user = Auth::user(); // ✅ IDE friendly

    if ($user->active_role === 'mediator') {
        // ...
    }
}
```

## 🚀 **Best Practices**

### **1. Import Facade di Controller**

```php
use Illuminate\Support\Facades\Auth;
```

### **2. Gunakan Variable untuk Multiple Access**

```php
// ❌ Tidak efisien
if (Auth::user()->active_role === 'mediator') {
    $mediator = Auth::user()->mediator;
}

// ✅ Lebih efisien
$user = Auth::user();
if ($user->active_role === 'mediator') {
    $mediator = $user->mediator;
}
```

### **3. Null Safety**

```php
$user = Auth::user();
if ($user && $user->active_role === 'mediator') {
    // Safe to access user properties
}
```

### **4. Blade Templates**

```php
{{-- Gunakan auth() helper di blade --}}
@if(auth()->check())
    Hello, {{ auth()->user()->name }}
@endif
```

## 🎯 **Kesimpulan**

-   **`Auth::user()`**: Lebih baik untuk controller dan complex logic
-   **`auth()->user()`**: Lebih baik untuk blade templates dan simple checks
-   **Kedua sintaks**: Melakukan hal yang sama secara fungsional
-   **Pilihan**: Bergantung pada konteks dan preferensi tim

## 📝 **Catatan Penting**

1. **Fungsionalitas**: Kedua sintaks memberikan hasil yang sama
2. **Performance**: Perbedaan sangat minimal
3. **IDE Support**: `Auth::user()` memberikan dukungan IDE yang lebih baik
4. **Consistency**: Pilih satu dan gunakan secara konsisten dalam project

## 🔄 **Migration Tips**

Jika ingin migrasi dari `auth()->user()` ke `Auth::user()`:

1. **Tambahkan import** di controller:

    ```php
    use Illuminate\Support\Facades\Auth;
    ```

2. **Ganti semua instance**:

    ```php
    // Dari
    $user = auth()->user();

    // Ke
    $user = Auth::user();
    ```

3. **Test thoroughly** untuk memastikan tidak ada breaking changes

4. **Update documentation** untuk tim development
