<template>
  <footer class="footer py-2">
    <div class="container">
      <div class="row align-items-center flex-row-reverse">
        <div class="col-md-12 col-sm-12 mt-3 mt-lg-0 text-center">
          <slot></slot>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: "Footer"
}
</script>
