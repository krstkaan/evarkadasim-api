# 🧠 Roomiefies API - Laravel Backend

Bu proje, Roomiefies mobil uygulamasının Laravel tabanlı RESTful API servisidir. Kullanıcı kimlik doğrulaması, karakter testi, ilan yönetimi, eşleşme algoritması altyapısı ve favori sistemi gibi özellikleri destekler.

---

## ✅ Yapılanlar

- 🔐 **JWT ile Kimlik Doğrulama:**
  - Laravel Sanctum yerine Firebase JWT kütüphanesi kullanılarak özel JWT tabanlı auth sistemi kuruldu.
  - Login, Register ve `/me` endpoint'leri hazırlandı.
  
- 👤 **Kullanıcı Yönetimi:**
  - Doğum tarihi, cinsiyet, il, ilçe, profil fotoğrafı gibi bilgilerle kullanıcı profili yönetilir.
  - Karakter testi sonucu kullanıcıya ait sınıf olarak veritabanında tutulur.

- 🧠 **Karakter Testi API'si:**
  - Test soruları ve sonuçları backend üzerinden yönetilir.
  - Kullanıcının testi tamamlamasıyla sınıf tayini yapılır.

- 🏠 **İlan İşlemleri:**
  - İlan oluşturma, düzenleme, silme ve kendi ilanlarını listeleme endpoint'leri eklendi.
  - İlanlara maksimum 3 görsel yüklenebilir. Görsel dosyaları `storage/app/public` klasöründe saklanır ve symlink ile public erişim sağlanır.

- ❤️ **Favori Sistemi:**
  - Kullanıcı bir ilanı favorileyebilir ve favori ilanları görüntüleyebilir.
  - `/favorites/toggle` ve `/favorites/check` endpoint'leri ile favori kontrolü sağlanır.

---

## 🔧 Yapılacaklar

- 🧬 **Eşleşme Algoritması:**
  - Karakter testinden alınan sınıfa göre ilanları filtreleyip eşleşme puanı hesaplayan yapı geliştirilecek.
  - Kullanıcı sadece kendisiyle uyumlu ev ilanlarını öncelikli olarak görecek.

- 📊 **Geri Bildirim Sistemi:**
  - Ev arkadaşlığı başlatıldığında taraflardan belirli aralıklarla değerlendirme alınacak.
  - Bu geri bildirimler, eşleşme algoritmasını sürekli iyileştirecek şekilde sisteme entegre edilecek.

- 💬 **Gerçek Zamanlı Sohbet:**
  - Laravel WebSockets ya da Pusher ile ilan sahibine mesaj gönderilebilecek gerçek zamanlı sohbet altyapısı planlanmaktadır.

---
