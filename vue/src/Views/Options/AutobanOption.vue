<template>
  <PageLayout>
    <div class="card">
      <div class="card-body">
        <autoban-table
          type="options"
          :autoban="autoban"
          :filters="filters"
          @autobanOptionDialog="autobanOptionDialog"
          @page="AutobanStore.initAutobans"
          @sort="sort"
          @AutobanReviewed="AutobanReviewed"
        />
      </div>
    </div>
    <Dialog
      modal class="modal-content modal-lg" content-class="modal-body"
      v-model:visible="autobanOptionDialogShow">
      <AutobanOptionController
        :selectedAutoban="selectedAutoban"
        @handleAutobanOptions="handleAutobanOptions"
      />
    </Dialog>
  </PageLayout>
</template>

<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue";
import AutobanTable from "../../components/Autoban/AutobanTable.vue";
import AutobanOptionController from "../../components/Option/AutobanOptionController.vue";
import Dialog from "primevue/dialog";

import {computed, inject, ref} from "vue";

import {useOptionStore} from "../../store/Options";
import {useAutobanStore} from "../../store/Autoban";
import {FilterMatchMode} from "primevue/api";
import {useAutobanOptionsStore} from "../../store/AutobanOptions";
import {useToast} from "primevue/usetoast";


const toast = useToast();
const t = inject('t')

const filters = ref({'global': { value: null, matchMode: FilterMatchMode.CONTAINS }})
const filter = ref('')

const AutobanStore = useAutobanStore()
const autoban = computed(()=>AutobanStore.autobans)
AutobanStore.initAutobans()

const errors = ref([])
const loading = ref(false)
const selectedAutoban = ref({})

const AutobanOptionsStore = useAutobanOptionsStore()
const autobanOptionDialogShow = ref(false)
const autobanOptionDialog = async (autoban = {},modal = true)=>{
  errors.value = []
  autobanOptionDialogShow.value = !autobanOptionDialogShow.value
  selectedAutoban.value = autoban
}

const handleAutobanOptions = async (optionModel) => {
  try {
    loading.value = true
    let opModel = Object.keys(optionModel).reduce((a,b)=>{
      const x = optionModel[b]
      if ( typeof x === 'object' && x && x.length ) a[b] = x
      else if (typeof x !== 'object' && typeof x !== 'undefined') a[b] = x
      return a;
    },{})
    await AutobanStore.handleAutobanOption(selectedAutoban.value,opModel)
    autobanOptionDialogShow.value = !autobanOptionDialogShow.value
    toast.add({
      closable: false,
      severity: "success",
      summary: "Options",
      detail: "Success",
      life: 3000
    })
    loading.value = false
  } catch (e) {
    loading.value = false
    errors.value = e;
  }
}

const sort = (meta,filter,dataSort,sortDir)=>{
  AutobanStore.initAutobans(meta, filter, '', '',dataSort,sortDir)
}

const AutobanReviewed = async (v,autoban) => {
  await AutobanStore.handleAutobanReview(v, autoban)
  toast.add({
    closable: false,
    severity: "success",
    summary: "Options",
    detail: "Success",
    life: 3000
  })
}

</script>

<style>
  .step-progress__step{
    cursor: pointer
  }
  .step-progress__step:after {
    height: 50px !important;
    width: 50px !important;
  }
  .step-progress__step-label{
    top: calc(100% + 15px) !important;
  }
  .step-progress__step span{
    font-size: 35px !important;
  }
  .rtl .step-progress__wrapper-after {
    transform-origin: right center;
  }
</style>
