<template>
  <PageLayout>
    <button class="btn btn-primary mb-2">
      <i class="fe fe-plus"></i>
      Add New Role
    </button>
    <div class="row">
      <div class="col-xl-3">
        <div class="card">
          <div class="nav flex-column admisetting-tabs"
               role="tablist"
               aria-orientation="vertical">
            <Loading v-if="logFiles.loading" />
            <template v-else v-for="(file,index) in logFiles.data.structure" :key="index">
              <a v-if="typeof file !== 'object'"
                 class="nav-link d-flex justify-content-between"
                 data-bs-toggle="pill"
                 role="tab"
                 @click="fetchLog(file)"
                 href="#pill">
                <div> {{ file }} </div>
              </a>
              <div v-else class="nav-link accordion" id="accordionFlush">
                <div v-for="(folder,indx) in file" :key="indx" class="w-100">
                  <div class="row justify-content-center" data-bs-toggle="collapse" :data-bs-target="`#flush-collapse${indx}`">
                    <span class="d-block">{{ index }}</span>
                  </div>
                  <div :id="`flush-collapse${indx}`" class="accordion-collapse collapse" data-bs-parent="#accordionFlush">
                    <a class="nav-link d-flex justify-content-between"
                       data-bs-toggle="pill"
                       role="tab"
                       @click="fetchLog(folder,index)"
                       href="#pill">
                      <div> {{ folder }} </div>
                    </a>
                  </div>
                </div>
              </div>

            </template>
          </div>
        </div>
      </div>
      <div class="col-xl-9">
        <div class="tab-content">
          <div class="tab-pane" id="pill">
            <div class="card">
              <div class="card-body">
                <div class="row text-center">
                  <DataTable :loading="log.loading" :value="log.data"
                             :filters="filters" :rows-per-page-options="[15,30,60]"
                             row-group-mode="rowspan" group-rows-by="table_name" paginator :rows="15"
                             paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                             current-page-report-template="Showing {first} to {last} of {totalRecords}"
                             table-class="table table-sm table-vcenter text-nowrap table-bordered border-bottom table-striped table-hover">
                    <template #loading>
                      <Loading />
                    </template>
                    <template #header>
                      <div class="search-element w-25 ms-auto mx-3 mb-4">
                        <input type="search" class="form-control header-search"
                               v-model="filters['global'].value" placeholder="Search…"
                               aria-label="Search" tabindex="1">
                        <i class="feather feather-search position-absolute"
                           style="top: 30%;right: 10px"></i>
                      </div>
                    </template>
                    <Column field="level" header="Level" :sortable="true">
                      <template #body="val">
                        <span :class="`text-${val.data.level_class}`" style="overflow: hidden !important;">
                          <i :class="`feather feather-${val.data.level}`"></i>
                          {{ val.data[val.field] }}
                        </span>
                      </template>
                    </Column>
                    <Column field="text" header="text" :sortable="true" class="overflow-auto w-70">
                      <template #body="value">
                        <pre>{{ JSON.parse(value.data.text) }}</pre>
                      </template>
                    </Column>
                    <Column field="date" header="date" :sortable="true" />
                  </DataTable>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </PageLayout>
</template>

<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue"
import Loading from "/src/components/Loading.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import {useLogStore} from "../../store/Log";
import {computed, ref} from "vue";
import {FilterMatchMode} from "primevue/api";

const filters = ref({'global': { value: null, matchMode: FilterMatchMode.CONTAINS }})
const logStore = useLogStore()
const logFiles = computed(()=>logStore.logFiles)
logStore.initFiles();

const log = ref({})
const fetchLog = async (file,folder = null)=>{
  await logStore.initLog(file,folder)
  log.value = logStore.log
}

</script>

<style lang="scss" scoped>
::v-deep(.p-paginator) {
  .p-paginator-current {
    margin-left: auto;
  }
}
</style>
