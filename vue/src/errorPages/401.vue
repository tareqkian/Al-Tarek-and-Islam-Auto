<template>
  <div class="page responsive-log error-bg">
    <div class="page-content m-0">
      <div class="container text-center relative">
        <div class="display-1 mb-5 font-weight-semibold text-danger">
          401
        </div>
        <h1 class="h3 mb-3 font-weight-semibold text-warning">
          Un Authorized Access!
        </h1>
        <p class="h5 font-weight-normal mb-7 leading-normal text-muted">
          You may have mistyped the address.
        </p>
        <router-link to="/" class="btn btn-primary">
          <i class="fe fe-arrow-left-circle me-1"></i>
          Back to Home
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "401"
}
</script>
