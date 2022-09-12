<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue"
</script>
<template>
  <PageLayout :meta="this.$route.meta">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            Typing Something text here....
            <br>
            <pre>{{$can("browse_menus")}}</pre>
          </div>
        </div>
      </div>
    </div>
  </PageLayout>
</template>
