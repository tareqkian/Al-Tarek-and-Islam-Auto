<template>
  <PageLayout>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            Admin's Dashboard
            <pre>{{$can("browse_menus")}}</pre>
          </div>
        </div>
      </div>
    </div>
  </PageLayout>
</template>

<script setup>
import PageLayout from "/src/components/Layouts/PageLayout.vue"
</script>
