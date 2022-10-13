<template>
  <PageLayout :meta="this.$route.meta">
    <button v-if="$can(`add_${this.$route.meta.permissionsLayout}`)"
            class="btn btn-primary mb-2"
            @click="taskDialog()">
      <i class="fe fe-plus"></i>
      Add New Task
    </button>
    <div class="row">
      <div class="col-xl-3 Flipped" style="max-height: 700px; overflow: auto">

        <div class="card Content">
          <div class="nav flex-column admisetting-tabs"
               role="tablist"
               aria-orientation="vertical">
            <a class="nav-link d-flex justify-content-between active ps-5"
               data-bs-toggle="pill"
               @click="selecteTask('all')">
              <div class="ps-5 ms-4"> All </div>
            </a>

            <div class="card-header border-top py-4">
              <h3 class="card-title">
                Late Tasks
              </h3>
            </div>
            <Loading v-if="tasks.loading" />
            <a v-else
               class="nav-link d-flex justify-content-between ps-3"
               data-bs-toggle="pill"
               v-for="(task, index) in lateTasks" :key="index"
               @click="selecteTask(task)" role="tab">
              <div>
                <img :src="task.brand.brand_image" :alt="t(task.brand,'brand_title')" width="40" class="me-2">
                {{ t(task.brand,"brand_title") }}
                <small class="text-muted ms-3"> {{ t(task,"lateDate") }} </small>
              </div>
              <div v-if="$can(`edit_${this.$route.meta.permissionsLayout}`) || $can(`delete_${this.$route.meta.permissionsLayout}`)">
                <i v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)" class="fa fa-edit text-info mx-1" @click="taskDialog(task)"></i>
                <i v-if="$can(`delete_${this.$route.meta.permissionsLayout}`)" class="fa fa-trash text-danger mx-1" @click="taskDelete($event,task)"></i>
              </div>
            </a>
            <span v-if="!lateTasks.length && !tasks.loading" class="ps-3 py-2">
                No Late Tasks
            </span>

            <div class="card-header border-top py-4">
              <h3 class="card-title">
                Current Tasks
              </h3>
            </div>
            <Loading v-if="tasks.loading" />
            <a v-else
               class="nav-link d-flex justify-content-between ps-3"
               data-bs-toggle="pill"
               v-for="(task, index) in currentTasks" :key="index"
               @click="selecteTask(task)" role="tab">
              <div>
                <img :src="task.brand.brand_image" :alt="t(task.brand,'brand_title')" width="40" class="me-2">
                {{ t(task.brand,"brand_title") }}
                <small class="text-muted ms-3"> {{ t(task,"currentDate") }} </small>
              </div>
              <div v-if="$can(`edit_${this.$route.meta.permissionsLayout}`) || $can(`delete_${this.$route.meta.permissionsLayout}`)">
                <i v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)" class="fa fa-edit text-info mx-1" @click="taskDialog(task)"></i>
                <i v-if="$can(`delete_${this.$route.meta.permissionsLayout}`)" class="fa fa-trash text-danger mx-1" @click="taskDelete($event,task)"></i>
              </div>
            </a>
            <span v-if="!currentTasks.length && !tasks.loading" class="ps-3 py-2">
                No Current tasks
            </span>

            <div class="card-header border-top py-4">
              <h3 class="card-title">
                Upcoming Tasks
              </h3>
            </div>
            <Loading v-if="tasks.loading" />
            <a v-else
               class="nav-link d-flex justify-content-between ps-3"
               data-bs-toggle="pill"
               v-for="(task, index) in upcomingTasks" :key="index"
               @click="selecteTask(task)" role="tab">
              <div>
                <img :src="task.brand.brand_image" :alt="t(task.brand,'brand_title')" width="40" class="me-2">
                {{ t(task.brand,"brand_title") }}
                <small class="text-muted ms-3"> {{ t(task,"upcomingDate") }} </small>
              </div>
              <div v-if="$can(`edit_${this.$route.meta.permissionsLayout}`) || $can(`delete_${this.$route.meta.permissionsLayout}`)">
                <i v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)" class="fa fa-edit text-info mx-1" @click="taskDialog(task)"></i>
                <i v-if="$can(`delete_${this.$route.meta.permissionsLayout}`)" class="fa fa-trash text-danger mx-1" @click="taskDelete($event,task)"></i>
              </div>
            </a>
            <span v-if="!upcomingTasks.length && !tasks.loading" class="ps-3 py-2">
                No Upcoming tasks
            </span>

          </div>
        </div>

      </div>
      <div class="col-xl-9">
        <div class="tab-content">
          <div role="tabpanel">
            <div class="card">
              <div class="card-body">
                <DataTable :loading="autoban.loading"
                           :value="autoban.data"
                           :filters="filters"

                           rowGroupMode="rowspan"
                           groupRowsBy="model.model_title"
                           sortMode="single"
                           :sortField="opt => `${opt.model.brand.brand_title} - ${opt.model.model_title} - ${opt.year.year_number} - ${opt.order}`"
                           :sortOrder="1"

                           table-class="table table-vcenter text-nowrap border-bottom table-striped table-hover">
                  <template #loading>
                    <Loading />
                  </template>
                  <template #header>
                    <div class="row flex-row-reverse justify-content-center justify-content-md-start w-100 m-0">
                      <div class="search-element col-10 col-md-3 mx-3 mb-4 p-0">
                        <input type="search" class="form-control header-search"
                               v-model="filter" placeholder="Search…"
                               aria-label="Search" tabindex="1">
                        <i class="feather feather-search position-absolute" style="top: 30%;right: 10px"></i>
                      </div>
                    </div>
                  </template>
                  <template #empty>
                    <p class="h5 text-center m-0 text-muted"> There is No Data </p>
                  </template>

                  <Column field="model.model_title" header="Model" header-style="width: 30%">
                    <template #body="value">
                      <Image width="50" :src="value.data.model.brand.brand_image" preview/>
                      <Image width="80" :src="value.data.model.model_image" preview/>
                      <span class="mx-2">
                    {{ `${t(value.data.model.brand,'brand_title')} - ${t(value.data.model,'model_title')}` }}
                  </span>
                    </template>
                  </Column>
                  <Column field="type" header="Type">
                    <template #body="value">
                      {{ t(value.data.type,'type_title') }}
                    </template>
                  </Column>
                  <Column field="year" header="year" header-style="width: 5%">
                    <template #body="value">
                      {{ t(value.data.year,'year_number') }}
                    </template>
                  </Column>
                  <Column field="price.official" header="official" header-style="width: 10%">
                    <template #body="value">
                      <InputNumber v-model="value.data.price.official"
                                   input-class="form-control form-control-sm"
                                   mode="decimal" />
                    </template>
                  </Column>
                  <Column field="price.commercial" header="commercial" header-style="width: 10%">
                    <template #body="value">
                      <InputNumber v-model="value.data.price.commercial"
                                   input-class="form-control form-control-sm"
                                   mode="decimal" />
                    </template>
                  </Column>
                  <Column field="price.sale" header="sale" header-style="width: 10%">
                    <template #body="value">
                      <InputNumber v-model="value.data.price.sale"
                                   input-class="form-control form-control-sm"
                                   mode="decimal" />
                    </template>
                  </Column>
                  <Column header-style="width: 3%" body-class="px-2">
                    <template #body="value">
                      <i @click="toggle($event,value.data)" class="text-primary fa fa-calculator"></i>
                    </template>
                  </Column>
                  <Column field="market_availability" header="availability" body-class="text-center" header-style="width: 5%">
                    <template #body="value">
                      <div class="form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               @change="price_list_appearance_market_availability(value.data)"
                               v-model='value.data.market_availability'>
                      </div>
                    </template>
                  </Column>
                  <Column header="Action" body-class="text-end" header-class="text-end">
                    <template #body="value">
                      <button @click.prevent="priceChange(value.data)" type="submit" class="btn btn-sm btn-primary">Confirm</button>
                    </template>
                  </Column>

                  <template #footer v-if="autoban.pagination">
                    <Paginator v-if="fetchedTask === 'all'"
                               :rows="+autoban.pagination.per_page"
                               :totalRecords="autoban.pagination.total"
                               :rowsPerPageOptions="[10,20,30]"
                               template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                               current-page-report-template="Showing {first} to {last} of {totalRecords}"
                               @page="AutobanStore.initAutobans($event,filter)"></Paginator>
                    <Paginator v-if="fetchedTask !== 'all'"
                               :rows="+autoban.pagination.per_page"
                               :totalRecords="autoban.pagination.total"
                               :rowsPerPageOptions="[10,20,30]"
                               template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                               current-page-report-template="Showing {first} to {last} of {totalRecords}"
                               @page="AutobanStore.initAutobanPriceTask(fetchedTask,$event)"></Paginator>
                  </template>
                </DataTable>

              </div>
            </div>
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
      v-model:visible="taskDialogShow">
      <form @submit.prevent="handleTasks">
        <div v-if="!selectedTask.id" class="form-floating my-2">
          <MultiSelect
            v-model="selectedTask.autoban_brand_id"
            :selectAll="false"
            :options="autobanBrands.data"
            :option-label="opt=>t(opt,'brand_title')"
            placeholder="Brands"
            class="form-control d-flex align-items-stretch"
            :class="[!errors.autoban_brand_id || 'is-invalid']"
            filter filter-placeholder="Search" >
            <template #value="slotProps">
              <div class="row">
                <div style="width: fit-content"
                     v-for="option of slotProps.value"
                     :key="option.id">
                  <img :src="option.brand_image" class="mr-2" width="27" />
                  {{option.brand_title}}
                </div>
              </div>
              <template v-if="!slotProps.value || slotProps.value.length === 0">
                Brand
              </template>
            </template>
            <template #option="slotProps">
              <div class="country-item d-flex justify-content-start align-items-center">
                <img :src="slotProps.option.brand_image" class="mx-3" width="50" />
                {{slotProps.option.brand_title}}
              </div>
            </template>
          </MultiSelect>
          <label> Brand </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.autoban_brand_id" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <input class="form-control"
                 type="number"
                 v-model="selectedTask.duration"
                 :class="[!errors.duration || 'is-invalid']"
                 placeholder="...">

          <label> Duration </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.duration" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[!loading || 'btn-loading']">Save</button>
          <button type="button" class="btn btn-secondary" @click="taskDialogShow = !taskDialogShow">Close</button>
        </div>
      </form>
    </Dialog>
    <OverlayPanel ref="op"
                  :showCloseIcon="true"
                  :style="{width: '400px'}"
                  :breakpoints="{'960px': '75vw', '640px': '90vw'}">
      <div class="card-header pt-2 ps-1 mb-5">
        <img width="40" :src="priceCalc.currentWatch.model.brand.brand_image" :alt="priceCalc.currentWatch.model.brand.brand_title">
        <img width="40" :src="priceCalc.currentWatch.model.model_image" :alt="priceCalc.currentWatch.model.model_title">
        <h5 class="ms-2 m-0">{{ `${priceCalc.currentWatch.model.brand.brand_title} - ${priceCalc.currentWatch.model.model_title}` }}</h5>
      </div>
      <div class="card-body py-2 px-0">
        <div class="row">
          <div class="col-12">
            <div class="p-float-label d-flex justify-content-center mb-3">
              <InputNumber v-model="priceCalc.official"
                           @input="priceCalc.official = $event.value"
                           input-class="form-control text-center" mode="decimal" />
              <label>Official</label>
            </div>
          </div>
          <div class="col">
            <Toggle v-model="priceCalc.commercial.type" class="mb-5"
                    true-value="Over" on-label="Over"
                    false-value="Discount" off-label="Discount" />
            <div class="p-float-label d-flex mt-2">
              <InputNumber v-model="priceCalc.commercial.amount" @input="priceCalc.commercial.amount = $event.value" input-class="form-control" mode="decimal" />
              <label class="ms-2">Commercial</label>
            </div>
          </div>
          <div class="col">
            <Toggle v-model="priceCalc.sale.type" class="mb-5"
              true-value="Over" on-label="Over"
              false-value="Discount" off-label="Discount" />
            <div class="p-float-label d-flex mt-2">
              <InputNumber v-model="priceCalc.sale.amount" @input="priceCalc.sale.amount = $event.value" input-class="form-control" mode="decimal" />
              <label class="ms-2">Sale</label>
            </div>
          </div>

          <div class="col-12 text-center mt-3">
            <label>Profit</label>
            <h5 class="mb-0">{{t(priceCalc,'profit','en',true)}}</h5>
          </div>

        </div>
      </div>
    </OverlayPanel>
  </PageLayout>
</template>

<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue";
import Loading from "../../components/Loading.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Paginator from "primevue/paginator";
import Image from "primevue/image";
import InputNumber from 'primevue/inputnumber';
import Dialog from "primevue/dialog";
import Dropdown from "primevue/dropdown";
import MultiSelect from 'primevue/multiselect';
import dayjs from 'dayjs';
import OverlayPanel from 'primevue/overlaypanel';
import Toggle from '@vueform/toggle'


import {useToast} from "primevue/usetoast";
import {useConfirm} from "primevue/useconfirm";

import {computed, inject, reactive, ref, watch} from "vue";
import {useAutobanStore} from "../../store/Autoban";
import {FilterMatchMode} from "primevue/api";
import _debounce from "lodash/debounce";

const filters = ref({'global': { value: null, matchMode: FilterMatchMode.CONTAINS }})
const filter = ref()
watch(
  filter,
  _debounce((val)=>{
    AutobanStore.initAutobans({},val)
  },300)
)
const t = inject("t");
const toast = useToast();
const confirm = useConfirm();

const priceCalcOriginal = {
  official: ref(0),
  commercial: reactive({type: 'Discount', amount: 0}),
  sale: reactive({type: 'Discount', amount: 0}),
  profit: ref(0),
  currentWatch: ref(null)
}
const priceCalc = reactive({...priceCalcOriginal});
const resetPriceCalc = ()=>Object.assign(reactive(priceCalc),priceCalcOriginal)
const op = ref();
const toggle = async (event,data) => {
  await op.value.hide();
  op.value.toggle(event,event.target);
  resetPriceCalc()
  Object.assign(priceCalc, {
    official: ref(data.price.official),
    commercial: {
      type: ( data.price.official > data.price.commercial ? 'Discount' : 'Over'),
      amount: Math.abs(data.price.official - data.price.commercial),
    },
    sale: {
      type: ( data.price.official > data.price.sale ? 'Discount' : 'Over'),
      amount: Math.abs(data.price.official - data.price.sale),
    },
    profit: ( data.price.sale - data.price.commercial ),
    currentWatch: ref(data)
  });
};

watch(priceCalc, async (val)=>{
  let price = val.currentWatch.price;
  price.official = val.official
  price.commercial = (price.official + ( val.commercial.type === 'Discount' ? -val.commercial.amount : val.commercial.amount))
  price.sale = (price.official + ( val.sale.type === 'Discount' ? -val.sale.amount : val.sale.amount))
  val.profit = (price.sale - price.commercial)
})

const AutobanStore = useAutobanStore()
const autoban = computed(()=>AutobanStore.autobans)
AutobanStore.initAutobans()
const tasks = computed(()=>AutobanStore.autobanPriceTasks)
const lateTasks = computed(()=>{
  return tasks.value.data.filter(x=>{
    const today = dayjs(new Date()).format('YYYY-MM-DD');
    const date = new Date(x.updated_at);
    const dayJs = dayjs(date.setDate(date.getDate() + +x.duration)).format('YYYY-MM-DD');
    return dayJs < today;
  }).map(x=> {
    const date = new Date(x.updated_at);
    const lateDate = dayjs(date.setDate(date.getDate() + +x.duration)).format('YYYY-MM-DD');
    x.lateDate = lateDate
    return x
  })
})
const currentTasks = computed(()=>{
  return tasks.value.data.filter(x=>{
    const today = dayjs(new Date()).format('YYYY-MM-DD');
    const date = new Date(x.updated_at);
    const dayJs = dayjs(date.setDate(date.getDate() + +x.duration)).format('YYYY-MM-DD');
    return dayJs === today;
  }).map(x=> {
    const date = new Date(x.updated_at);
    const currentDate = dayjs(date.setDate(date.getDate() + +x.duration)).format('YYYY-MM-DD');
    x.currentDate = currentDate
    return x
  })
})
const upcomingTasks = computed(()=>{
  return tasks.value.data.filter(x=>{
    const today = dayjs(new Date()).format('YYYY-MM-DD');
    const date = new Date(x.updated_at);
    const dayJs = dayjs(date.setDate(date.getDate() + +x.duration)).format('YYYY-MM-DD');
    return dayJs > today;
  }).map(x=> {
    const date = new Date(x.updated_at);
    const upcomingDate = dayjs(date.setDate(date.getDate() + +x.duration)).format('YYYY-MM-DD');
    x.upcomingDate = upcomingDate
    return x
  })
})

AutobanStore.initAutobanPriceTasks()

const autobanBrands = computed(()=>AutobanStore.autobanBrands)
const loading = ref(false)
const errors = ref([])

const selectedTask = ref({
  id: null,
  autoban_brand_id: null,
  duration: null,
});
const taskDialogShow = ref(false);
const taskDialog = (task = {}) => {
  errors.value = []
  taskDialogShow.value = !taskDialogShow.value
  AutobanStore.initAutobanBrands()
  selectedTask.value = {
    id: (task.id || null),
    autoban_brand_id: (task.brand ? task.brand.id : null),
    duration: (task.duration || null),
  }
}

const handleTasks = async () => {
  try {
    loading.value = true
    errors.value = []
    await AutobanStore.handleAutobanPriceTasks(selectedTask.value)
    taskDialogShow.value = !taskDialogShow.value
    loading.value = false
  } catch (e) {
    loading.value = false
    errors.value = e
  }
}

const taskDelete = (event,task) => {
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
        await AutobanStore.distroyAutobanPriceTask(task)
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

const fetchedTask = ref('all');
const selecteTask = (task) => {
  fetchedTask.value = task;
  if ( task === 'all' ) AutobanStore.initAutobans()
  else AutobanStore.initAutobanPriceTask(task)
}

const priceChange = async (price)=>{
  try {
    const taskIndex = tasks.value.data.findIndex(x=>x.brand.id===price.model.brand.id);
    loading.value = true
    await AutobanStore.handleAutobanPrices(price.price)
    loading.value = false
    toast.add({
      closable: false,
      severity: "success",
      summary: "price",
      detail: "Updated",
      life: 3000
    })
    if ( taskIndex !== -1 ) {
      const task = tasks.value.data[taskIndex]

      const today = dayjs(new Date()).format('YYYY-MM-DD');
      const date = new Date(task.updated_at);
      const dayJs = dayjs(date.setDate(date.getDate() + +task.duration)).format('YYYY-MM-DD');

      confirm.require({
        group: 'dialogConfirmation',
        header: 'Confirmation',
        acceptClass: 'btn btn-primary mx-1',
        rejectClass: 'btn btn-secondary mx-1',
        message: `${task.brand.brand_title} date ${dayJs} and duration is ${task.duration}, You want to Update It as today task`,
        accept: async () => {
          task.autoban_brand_id = task.brand.id
          task.touch = true
          await AutobanStore.handleAutobanPriceTasks(task)
          toast.add({
            closable: false,
            severity: 'info',
            summary: 'Confirmed',
            detail: 'You have accepted',
            life: 3000
          });
        },
        reject: () => {
          toast.add({
            closable: false,
            severity: 'danger',
            summary: 'Rejected',
            detail: 'You have rejected',
            life: 3000
          });
        },
      });
    }
  } catch (e) {
    errors.value = e
    toast.add({
      closable: false,
      severity: "danger",
      summary: "price",
      detail: e,
      life: 3000
    })
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

/*Echo.channel("PricesEvent")
  .listen('TaskAdder',(data)=>{
    if ( AutobanStore.autobanPriceTasks.data.length ) {
        AutobanStore.autobanPriceTasks.data = [...AutobanStore.autobanPriceTasks.data, ...data.tasks]
    }
  })
  .listen('TaskEditor',({task})=>{
    const taskIndex = AutobanStore.autobanPriceTasks.data.findIndex(x => x.id === task.id);
    AutobanStore.autobanPriceTasks.data = [
      ...AutobanStore.autobanPriceTasks.data.slice(0,taskIndex),
      {...task},
      ...AutobanStore.autobanPriceTasks.data.slice(taskIndex+1)
    ]
  })
  .listen('TaskDeleter',({task})=>{
    const taskIndex = AutobanStore.autobanPriceTasks.data.findIndex(x => x.id === task.id);
    AutobanStore.autobanPriceTasks.data = [
      ...AutobanStore.autobanPriceTasks.data.slice(0,taskIndex),
      ...AutobanStore.autobanPriceTasks.data.slice(taskIndex+1)
    ]
  })*/
</script>

<style src="@vueform/toggle/themes/default.css"></style>
