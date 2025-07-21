<?php /* Modern Navbar dengan user info kanan */ ?>
<style>
.navbar-custom {
  width: 100%;
  background: #fff;
  box-shadow: 0 2px 12px rgba(44,62,80,0.07);
  padding: 0.7rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 100;
}
.navbar-brand {
  font-weight: 700;
  font-size: 1.3rem;
  color: #6a82fb;
  letter-spacing: 1px;
  text-decoration: none;
}
.navbar-menu {
  display: flex;
  gap: 1.2rem;
  align-items: center;
}
.navbar-menu a {
  color: #4a5a6a;
  text-decoration: none;
  font-weight: 500;
  font-size: 1rem;
  padding: 0.3rem 0.7rem;
  border-radius: 6px;
  transition: background 0.2s, color 0.2s;
}
.navbar-menu a:hover, .navbar-menu a.active {
  background: #f3f6fa;
  color: #6a82fb;
}
.navbar-user {
  position: relative;
  margin-left: 1.5rem;
}
.navbar-user-btn {
  background: none;
  border: none;
  font-family: 'Poppins', Arial, sans-serif;
  font-size: 1rem;
  color: #4a5a6a;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.navbar-user-dropdown {
  display: none;
  position: absolute;
  right: 0;
  top: 2.2rem;
  background: #fff;
  box-shadow: 0 4px 16px rgba(44,62,80,0.10);
  border-radius: 10px;
  min-width: 160px;
  z-index: 10;
}
.navbar-user-dropdown a {
  display: block;
  padding: 0.8rem 1.2rem;
  color: #4a5a6a;
  text-decoration: none;
  font-size: 1rem;
  border-radius: 0;
}
.navbar-user-dropdown a:hover {
  background: #f3f6fa;
  color: #6a82fb;
}
.navbar-user.open .navbar-user-dropdown {
  display: block;
}
@media (max-width: 700px) {
  .navbar-custom { flex-direction: column; align-items: flex-start; padding: 0.7rem 0.5rem; }
  .navbar-menu { flex-wrap: wrap; gap: 0.7rem; }
}
</style>
<div class="navbar-custom">
  <a href="./" class="navbar-brand">SPK SAW</a>
  <nav class="navbar-menu">
    <a href="./" class="<?= basename($_SERVER['PHP_SELF'])=='index.php'?'active':'' ?>">Dashboard</a>
    <a href="alternatif.php" class="<?= basename($_SERVER['PHP_SELF'])=='alternatif.php'?'active':'' ?>">Alternatif</a>
    <a href="bobot.php" class="<?= basename($_SERVER['PHP_SELF'])=='bobot.php'?'active':'' ?>">Bobot & Kriteria</a>
    <a href="matrik.php" class="<?= basename($_SERVER['PHP_SELF'])=='matrik.php'?'active':'' ?>">Matrik</a>
    <a href="preferensi.php" class="<?= basename($_SERVER['PHP_SELF'])=='preferensi.php'?'active':'' ?>">Preferensi</a>
    <a href="export/cetak-laporan-lengkap.php" target="_blank">Cetak Laporan</a>
  </nav>
  <div class="navbar-user" id="navbarUser">
    <button class="navbar-user-btn" onclick="document.getElementById('navbarUser').classList.toggle('open')">
      <span>👤 Admin</span>
      <svg width="16" height="16" fill="none" stroke="#4a5a6a" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
    </button>
    <div class="navbar-user-dropdown">
      <a href="#" style="pointer-events:none;opacity:0.7;">Profile (Admin)</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>
</div>
<script>
document.addEventListener('click', function(e) {
  var user = document.getElementById('navbarUser');
  if (!user) return;
  if (!user.contains(e.target)) user.classList.remove('open');
});
</script> 