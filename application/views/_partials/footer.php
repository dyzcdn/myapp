</main>

<footer class="bg-light text-center text-muted py-3 mt-auto border-top">
  <small>&copy; <?= date('Y') . ' ' . $this->config->item('site_name') ?>. All rights reserved.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('<?= base_url('sw.js') ?>')
      .then(reg => console.log('ServiceWorker registered', reg))
      .catch(err => console.warn('ServiceWorker failed', err));
  }
</script>

<?php if ($this->uri->segment(1) === 'certificates'): ?>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/datatables.net-responsive@2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/datatables.net-responsive-bs5@2.5.0/js/responsive.bootstrap5.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#certTable').DataTable({
        responsive: true,
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50, 100],
        language: {
          search: "<i class='fas fa-search'></i> Cari:",
          lengthMenu: "Tampilkan _MENU_ data",
          info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
          paginate: { next: "➡️", previous: "⬅️" }
        }
      });
    });
  </script>
<?php endif; ?>
</body>
</html>
