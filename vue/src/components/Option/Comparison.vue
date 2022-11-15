<template>
  <div class="dropdown header-notify">
    <a class="nav-link icon"
       @click="startComparison"
       data-bs-toggle="sidebar-right"
       :data-bs-target="autobans.length && autobans.length >= 2 ? `.autoban-comparison` : ``">
      <i class="fa fa-car header-icon"></i>
      <span v-if="autobans.length" class="badge badge-primary side-badge"> {{ autobans.length }} </span>
    </a>
  </div>
  <div class="sidebar sidebar-right autoban-comparison sidebar-animate h-100">
    <div class="card-header border-bottom pb-5">
      <h4 class="card-title"> Comparison </h4>
      <div class="card-options">
        <a  href="javascript:void(0);"
            class="btn btn-sm btn-icon btn-light text-primary"
            data-bs-toggle="sidebar-right"
            data-bs-target=".autoban-comparison">
          <i class="feather feather-x"></i>
        </a>
      </div>
    </div>
    <Loading v-if="autobansOptions.loading" class="my-5" />
    <div v-else class="card-body comparison-body">
      <ComparisonTable
        @removeFromComparison="removeFromComparison"
        :autobansOptions="autobansOptions"
        :optionTree="optionTree" />
    </div>
  </div>
</template>

<script setup>
import {useComparisonStore} from "../../store/AutobanOptionComparison";
import {computed, onActivated, onMounted, onRenderTriggered, onUpdated} from "vue";
import {useOptionStore} from "../../store/Options";
import Loading from "../Loading.vue"
import {useToast} from "primevue/usetoast";
import ComparisonTable from "./ComparisonTable.vue";

const toast = useToast();

const ComparisonStore = useComparisonStore()
const OptionStore = useOptionStore();
const autobans = computed(()=>ComparisonStore.autobans)
const autobansOptions = computed(()=>ComparisonStore.autobansOptions)
const optionTree = computed(()=>OptionStore.optionTree)

const removeFromComparison = (autoban)=>{
  ComparisonStore.removeAutobanFromComparison(autoban)
}

const startComparison = async ()=>{
  try {
    if ( autobans.value.length ) {
      if ( autobans.value.length >= 2 ) {
        OptionStore.initOptionTree()
        ComparisonStore.initComparison(autobans.value)
      } else {
        toast.add({
          closable: false,
          severity: "warning",
          summary: "Comparison",
          detail: "Minimum Cars To Compare ( 2 )",
          life: 3000
        })
      }
    } else {
      toast.add({
        closable: false,
        severity: "danger",
        summary: "Comparison",
        detail: "No Cars To Compare",
        life: 3000
      })
    }
  } catch (e) {
    throw e.response.data.errors
  }
}

</script>

<style lang="scss" scoped>
.autoban-comparison {
  width: 100% !important;
}
@media (min-width: 768px) {
  .autoban-comparison {
    width: 50% !important;
  }
}
.comparison-body{
  max-height: calc(100% - 83px);
  overflow: auto;
}
</style>
