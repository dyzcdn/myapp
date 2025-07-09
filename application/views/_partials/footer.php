</main>

<footer class="bg-light text-center text-muted py-3 mt-auto border-top">
    <small>&copy; <?= date('Y') . ' ' . $this->config->item('site_name') ?>. All rights reserved.</small>
</footer>

<script src="https://cdn.lab/static/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>

<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('<?= base_url('sw.js') ?>')
        .then(reg => console.log('ServiceWorker registered', reg))
        .catch(err => console.warn('ServiceWorker failed', err));
}
</script>


<?php if ($this->uri->segment(1) === 'certificates'): ?>
    <script src="https://cdn.lab/static/jQuery/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.lab/static/DataTables/datatables.min.js"></script>
    <script src="https://cdn.lab/static/dataTables/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.lab/static/dataTables/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.lab/static/dataTables/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#certTable').DataTable({
                responsive: true,
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50, 100],
                // order: [[0, 'desc']],
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
