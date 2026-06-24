
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

<!-- ===== FUNCION PARA AJUSTAR ESPACIO DEL FOOTER ===== -->
<script>
  function ajustarEspacioFooter() {
    const footer = document.getElementById("mainFooter");
    if (footer) {
      const footerHeight = footer.offsetHeight;
      // Se agrega un margen mayor (50px) entre el contenido y el footer
      document.body.style.paddingBottom = (footerHeight + 50) + "px";
    }
  }

  document.addEventListener("DOMContentLoaded", ajustarEspacioFooter);
  window.addEventListener("resize", ajustarEspacioFooter);
</script>

<!-- ===== FOOTER ===== -->
<footer id="mainFooter" class="py-3 text-light text-center"
  style="background-color: #000000ff; position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1000;">
  <div class="text-center mt-2 small">
    &copy; 2025 Cal Ko. Todos los derechos reservados.
  </div>
</footer>



</html>