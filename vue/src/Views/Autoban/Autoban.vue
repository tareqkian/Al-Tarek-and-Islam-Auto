<template>
  <PageLayout :meta="this.$route.meta">
    <div class="row">
      <div class="col">
        <button type="button" v-if="$can(`add_${this.$route.meta.permissionsLayout}`)" class="btn btn-primary mb-2 me-3" @click="autobanDialog()">
          <i class="fe fe-plus"></i>
          Add Autoban
        </button>
        <div class="card">
          <div class="card-body">
            <DataTable :loading="autoban.loading"
                       :value="autoban.data"
                       :filters="filters"
                       :rows-per-page-options="[10,20,30]"

                       rowGroupMode="rowspan"
                       groupRowsBy="model.model_title"
                       sortMode="single"
                       :sortField="opt => `${opt.model.brand.brand_title} - ${opt.model.model_title} - ${opt.year.year_number} - ${opt.order}`"
                       :sortOrder="1"
                       @rowReorder="onRowReorder($event)"

                       table-class="table table-vcenter text-nowrap border-bottom table-striped table-hover">
              <template #loading>
                <Loading />
              </template>
              <template #header>
                <div class="row flex-row-reverse justify-content-center justify-content-md-start w-100 m-0">
                  <div class="search-element col-10 col-md-3 mx-3 mb-4 p-0">
                    <input type="search" class="form-control header-search"
                           v-model="filters['global'].value" placeholder="Search…"
                           aria-label="Search" tabindex="1">
                    <i class="feather feather-search position-absolute" style="top: 30%;right: 10px"></i>
                  </div>
                </div>
              </template>

              <Column field="model.model_title" header="Model" headerStyle="width: 25%">
                <template #body="value">
                  <Image width="50" :src="value.data.model.brand.brand_image" preview/>
                  <Image width="80" :src="value.data.model.model_image" preview/>
                  <span class="mx-2">
                    {{ `${t(value.data.model.brand,'brand_title')} - ${t(value.data.model,'model_title')}` }}
                  </span>
                </template>
              </Column>
              <Column :rowReorder="true" headerStyle="width: 3rem" :reorderableColumn="false" />
              <Column field="type" header="Type">
                <template #body="value">
                  {{ t(value.data.type,'type_title') }}
                </template>
              </Column>
              <Column field="year" header="year">
                <template #body="value">
                  {{ t(value.data.year,'year_number') }}
                </template>
              </Column>
              <Column field="price.official" header="official">
                <template #body="value">
                  {{ t(value.data.price,'official',null,true) }}
                </template>
              </Column>
              <Column field="price.commercial" header="commercial">
                <template #body="value">
                  {{ t(value.data.price,'commercial',null,true) }}
                </template>
              </Column>
              <Column field="price.sale" header="sale">
                <template #body="value">
                  {{ t(value.data.price,'sale',null,true) }}
                </template>
              </Column>
              <Column field="price_list_appearance" header="appearance">
                <template #body="value">
                  <div class="form-switch">
                    <input class="form-check-input"
                           type="checkbox"
                           @change="price_list_appearance_market_availability(value.data)"
                           v-model='value.data.price_list_appearance'>
                  </div>
                </template>
              </Column>
              <Column field="market_availability" header="availability">
                <template #body="value">
                  <div class="form-switch">
                    <input class="form-check-input"
                           type="checkbox"
                           @change="price_list_appearance_market_availability(value.data)"
                           v-model='value.data.market_availability'>
                  </div>
                </template>
              </Column>
              <Column v-if="$can(`edit_${this.$route.meta.permissionsLayout}`) || $can(`delete_${this.$route.meta.permissionsLayout}`)" header="Actions">
                <template #body="val">
                  <i class="fa fa-edit text-info mx-1"
                     v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)"
                     @click="autobanDialog(val.data)"></i>
                  <i class="fa fa-trash text-danger mx-1"
                     v-if="$can(`delete_${this.$route.meta.permissionsLayout}`)"
                     @click="autobanDelete($event,val.data)"></i>
                </template>
              </Column>

              <template #footer v-if="autoban.pagination">
                <Paginator :rows="+autoban.pagination.per_page"
                           :totalRecords="autoban.pagination.total"
                           :rowsPerPageOptions="[10,20,30]"
                           template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                           current-page-report-template="Showing {first} to {last} of {totalRecords}"
                           @page="AutobanStore.initAutobans($event)"></Paginator>
              </template>
            </DataTable>
          </div>
        </div>
      </div>
    </div>
    <Dialog
      modal
      dismissableMask
      class="modal-content modal-lg"
      content-class="modal-body"
      :showHeader="false"
      v-model:visible="autobanDialogShow">
      <form @submit.prevent="handleAutoban">

        <div class="form-floating my-2">
          <Dropdown
            v-model="selectedAutoban.autoban_model_id"
            :options="autobanModels.data"
            :loading="autobanModels.loading"
            filter
            filter-placeholder="Search"
            option-value="id"
            :option-label="opt=>`${t(opt.brand,'brand_title')} - ${t(opt,'model_title')}`"
            class="form-control d-flex align-items-stretch"
            :class="[errors.autoban_model_id ? 'is-invalid' : '']"
            placeholder="Model" />
          <label> Model </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.autoban_model_id" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <Dropdown
            v-model="selectedAutoban.autoban_type_id"
            :options="autobanTypes.data"
            :loading="autobanTypes.loading"
            filter
            filter-placeholder="Search"
            option-value="id"
            :option-label="opt=>t(opt,'type_title')"
            class="form-control d-flex align-items-stretch"
            :class="[errors.autoban_type_id ? 'is-invalid' : '']"
            placeholder="Type" />
          <label> Type </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.autoban_type_id" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <Dropdown
            v-model="selectedAutoban.autoban_year_id"
            :options="autobanYears.data"
            :loading="autobanYears.loading"
            filter
            filter-placeholder="Search"
            option-value="id"
            :option-label="opt=>t(opt,'year_number')"
            class="form-control d-flex align-items-stretch"
            :class="[errors.autoban_year_id ? 'is-invalid' : '']"
            placeholder="Year" />
          <label> Year </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.autoban_year_id" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[!loading || 'btn-loading']">Save</button>
          <button type="button" class="btn btn-secondary" @click="autobanDialogShow = !autobanDialogShow">Close</button>
        </div>
      </form>
    </Dialog>
  </PageLayout>
</template>

<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue";
import Dialog from "primevue/dialog";
import Loading from "../../components/Loading.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Paginator from "primevue/paginator";
import Dropdown from "primevue/dropdown";
import Image from "primevue/image";

import {useToast} from "primevue/usetoast";
import {useConfirm} from "primevue/useconfirm";

import {computed, inject, ref} from "vue";
import {useAutobanStore} from "../../store/Autoban";
import {FilterMatchMode} from "primevue/api";

const filters = ref({'global': { value: null, matchMode: FilterMatchMode.CONTAINS }})
const t = inject("t");
const toast = useToast();
const confirm = useConfirm();

const AutobanStore = useAutobanStore()

const autobanModels = computed(()=>AutobanStore.autobanModels)
const autobanTypes = computed(()=>AutobanStore.autobanTypes)
const autobanYears = computed(()=>AutobanStore.autobanYears)
const autoban = computed(()=>AutobanStore.autobans)
AutobanStore.initAutobans()

const onRowReorder = async (event) => {
  let z = AutobanStore.autobans.data.filter(x=>JSON.stringify(x).toLowerCase().includes((filters.value.global.value || '').toLowerCase()))
  let order = 0;
  for (let index = 0; index < z.length; index++) {
    let x = event.value[index];
    if (index && x.model.id !== event.value[index-1].model.id ) order = 0
    const autobanIndex = AutobanStore.autobans.data.findIndex(z => z.id === x.id);
    x.order = order
    AutobanStore.autobans.data[autobanIndex] = {...x}
    order++
  }
  AutobanStore.autobans.data = AutobanStore.autobans.data.sort((a,b) => a.order - b.order);
  await AutobanStore.reOrder(z)
  toast.add({
    closable: false,
    severity: "success",
    summary: "autoban",
    detail: "Ordered",
    life: 3000
  })
}

const errors = ref([])
const loading = ref(false);

const autobanDialogShow = ref(false)
const selectedAutoban = ref({
  id: null,
  autoban_model_id: null,
  autoban_type_id: null,
  autoban_year_id: null,
  price_list_appearance: null,
  market_availability: null,
})
const autobanDialog = (autoban = {})=>{
  errors.value = []
  autobanDialogShow.value = !autobanDialogShow.value
  AutobanStore.initAutobanModels()
  AutobanStore.initAutobanTypes()
  AutobanStore.initAutobanYears()
  selectedAutoban.value.id = autoban.id || null
  selectedAutoban.value.autoban_model_id = (autoban.model ? autoban.model.id : null)
  selectedAutoban.value.autoban_type_id = (autoban.type ? autoban.type.id : null)
  selectedAutoban.value.autoban_year_id = (autoban.year ? autoban.year.id : null)
  selectedAutoban.value.price_list_appearance = (autoban.id ? autoban.price_list_appearance : 1)
  selectedAutoban.value.market_availability = (autoban.id ? autoban.market_availability : 1)
}
const handleAutoban = async()=>{
  try {
    errors.value = []
    loading.value = true
    await AutobanStore.handleAutobans(selectedAutoban.value)
    autobanDialogShow.value = !autobanDialogShow.value
    loading.value = false
  } catch (e) {
    errors.value = e
    loading.value = false
  }
}

const price_list_appearance_market_availability = async(autoban)=>{
  try {
    autoban.autoban_model_id = autoban.model.id;
    autoban.autoban_type_id = autoban.type.id;
    autoban.autoban_year_id = autoban.year.id;
    await AutobanStore.handleAutobans(autoban)
    toast.add({
      closable: false,
      severity: "success",
      summary: "autoban",
      detail: "has been Updated",
      life: 3000
    })
  } catch (e) {
    toast.add({
      closable: false,
      severity: "danger",
      summary: "autoban",
      detail: e,
      life: 3000
    })
  }
}

const autobanDelete = (event,autoban)=>{
  confirm.require({
    target: event.currentTarget,
    message: {
      header: 'Are Ya Sure',
      body: "You won't be able to revert this!"
    },
    icon: 'pi pi-exclamation-triangle',
    acceptLabel: "Yes, delete it!",
    acceptClass: 'btn btn-sm btn-danger mx-1',
    rejectLabel: "Cancel",
    rejectClass: 'btn btn-sm btn-secondary mx-1',
    accept: async()=>{
      try {
        await AutobanStore.distroyAutoban(autoban)
        toast.add({
          closable: false,
          severity: "success",
          summary: "autoban",
          detail: "has been Deleted",
          life: 3000
        })
      } catch (e) {
        toast.add({
          closable: false,
          severity: "danger",
          summary: "autoban",
          detail: e,
          life: 3000
        })
      }
    }
  });
}

Echo.channel("AutobansEvent")
  .listen('AutobanAdder',({autoban})=>{
    if ( AutobanStore.autobans.data.length ) {
      AutobanStore.autobans.data = [...AutobanStore.autobans.data, autoban]
    } else {
      AutobanStore.autobans.data = [autoban]
    }
  })
  .listen('AutobanEditor',({autoban})=>{
    if ( AutobanStore.autobans.data.length ) {
      const autobanIndex = AutobanStore.autobans.data.findIndex(x => x.id === autoban.id);
      AutobanStore.autobans.data = [
        ...AutobanStore.autobans.data.slice(0,autobanIndex),
        {...autoban},
        ...AutobanStore.autobans.data.slice(autobanIndex+1)
      ]
    }
  })
  .listen('AutobanDeleter',({autoban})=>{
    if ( AutobanStore.autobans.data.length ) {
      const autobanIndex = AutobanStore.autobans.data.findIndex(x => x.id === autoban.id);
      AutobanStore.autobans.data = [
        ...AutobanStore.autobans.data.slice(0,autobanIndex),
        ...AutobanStore.autobans.data.slice(autobanIndex+1)
      ]
    }
  })

</script>
