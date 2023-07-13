<template>
  <CSmartTable
    header
    :items="items"
    :columns="[
      'id',
      'ip',
      'route',
      'created_at',
    ]"
    columnFilter
    itemsPerPageSelect
    :itemsPerPage="8"
    columnSorter
    :loading="loadSpinner"
    pagination
  >
    
  </CSmartTable>
</template>
<script>
import axios from 'axios'
import { CSmartTable } from '@coreui/vue-pro'

export default {
  name: 'Dashboard',
  components: {
    CSmartTable
  },
  data () {
    return {
      items: [],
      loadSpinner: true
    }
  },
  beforeCreate () {
    axios.interceptors.response.use('', error => {
      if (error.response.status === 401) this.$router.push('Login')
    })
    axios.get(`/sanctum/csrf-cookie`)
  },
  created () {
    axios
      .get('/api/logs/', {
        headers: {
          Authorization: 'Bearer ' + localStorage.token
        }
      })
      .then(res => {
        this.items = res.data
        this.loadSpinner = false
      }).catch(()=>{})
  }
}
</script>