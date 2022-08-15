<template>
  <page-layout :meta="this.$route.meta">
    <!--<button class="btn btn-primary mb-2" @click="roleDialog()">
      <i class="fe fe-plus"></i>
      Add New Role
    </button>-->
    <div class="row">
      <div class="col-xl-3">
        <div class="card">
          <div class="nav flex-column admisetting-tabs">
            <a v-for="(group, index) in settingsGroup"
               :key="index"
               class="nav-link"
               :class="[index || 'active']"
               data-bs-toggle="pill"
               :href="`#tab-${index}`">
              {{ group }}
            </a>
          </div>
        </div>
      </div>

      <div class="col-xl-9">
        <div class="tab-content adminsetting-content" id="setting-tabContent">

          <div v-for="(group, index) in settingsGroup"
               :key="index"
               class="tab-pane fade"
               :class="[index || 'show active']"
               :id="`tab-${index}`">

            <form @submit.prevent="handleSetting()">
              <div class="card">
                <div class="card-header border-0">
                  <h4 class="card-title">{{ group }}</h4>
                </div>
                <div class="card-body">
                  <div v-for="(sett,index) in settings.filter(x=>x.group === group)"
                       :key="index"
                       class="form-group">
                    <div v-if="sett.type === 'image'" class="row">
                      <div class="col-md-3">
                        <label class="form-label mb-0 mt-2">{{sett.display_name}}</label>
                      </div>
                      <div class="col-md-9">
                        <MyFileUpload v-model="setts[sett.id]" :defaultImg="setts[sett.id]" />
                      </div>
                    </div>

                    <div v-else class="row">
                      <div class="col-md-3">
                        <label class="form-label mb-0 mt-2">{{sett.display_name}}</label>
                      </div>
                      <div class="col-md-9">
                        <input :type="sett.type"
                               class="form-control"
                               :placeholder="sett.display_name"
                               v-model="setts[sett.id]">
                      </div>
                    </div>

                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-success" :class="[!loading || 'btn-loading']">Save Changes</button>
                </div>
              </div>
            </form>

          </div>

        </div>
      </div>
    </div>
  </page-layout>
</template>

<script setup>
import PageLayout from "/src/components/Layouts/PageLayout.vue"
import {computed, inject, ref} from "vue";
import {useSettingsStore} from "../../store/Settings";
import {useToast} from "primevue/usetoast";
import MyFileUpload from "/src/components/MyFileUpload.vue"

const settingStore = useSettingsStore();
const settings = computed(()=>settingStore.settings)
const settingsGroup = computed(()=>[...new Set(settings.value.map(x=>x.group))])
settingStore.initSettings();
const setts = computed(()=>{
  return settings.value.reduce((a,b)=>{
    a[b.id] = b.value
    return a;
  },{})
})
const toast = useToast();
const loading = ref(false);
const handleSetting = async()=>{
  loading.value = true;
  await settingStore.handleSettings(setts.value)
  toast.add({
    closable: false,
    severity: "success",
    summary: "Settings",
    detail: "has been Updated",
    life: 3000
  })
  loading.value = false;
}

const injectedSettings = inject("Settings")
</script>
