<?php defined('_JEXEC') or die('Restricted Access'); ?>

<script>
/*const tmp = <?php print $results; ?>;*/
const app = Vue.createApp({
  data() {
    return {
      mytoken: document.getElementById('mytoken').value,
      results: [],
      searchvendor: 'index.php?option=com_seviersd&format=json&task=po.searchvendor',
      getvendor: 'index.php?option=com_seviersd&format=json&task=po.getvendor',
      showMessage: '',
      month: [
        {id: 1, name: 'January'},
        {id: 2, name: 'February'},
        {id: 3, name: 'March'},
        {id: 4, name: 'April'},
        {id: 5, name: 'May'},
        {id: 6, name: 'June'},
        {id: 7, name: 'July'},
        {id: 8, name: 'August'},
        {id: 9, name: 'September'},
        {id: 10, name: 'October'},
        {id: 11, name: 'November'},
        {id: 12, name: 'December'},
      ],
      year: [
        {id: 2021, name: '2021'},
        {id: 2022, name: '2022'},
        {id: 2023, name: '2023'},
        {id: 2024, name: '2024'},
        {id: 2025, name: '2025'},
        {id: 2026, name: '2026'},
      ],
      options: [
        {id: '', name: ''},
        {id: 'a', name: 'A'},
        {id: 'b', name: 'B'},
        {id: 'ab', name: 'AB'},
      ],
      selectedMonth: '',
      selectedYear: '',
      lastYear: '',
      nextYear: '',
    }
  },
  methods: {
    async filterOptions() {
      await axios({
        method: 'get',
        url: `${this.searchvendor}&${this.mytoken}&name=${this.searchTerm}`,
      })
          .then(response => {
            this.options = response.data.results;
            console.log(response.data.results);
            this.filteredOptions = this.options;
            // this.filteredOptions = this.options.filter(option =>
            //     option.toLowerCase().includes(this.searchTerm.toLowerCase())
            // );
            //console.log('here');
            //console.log('filter='+this.filteredOptions);

            this.isDropdownOpen = true;
            this.showSpinnerFlag = false;
            if (response.data.error) {
              this.showMessage = response.data.error;
              this.showMessageFlag = true
            } else {
              this.showMessageFlag = false
            }
          })
          .catch(function (error) {
          })
    },
    async getab() {
      this.showSpinnerFlag = true;
      const formData = new FormData();
      formData.append('month', this.selectedMonth);
      formData.append('year', this.selectedYear);
      await axios({
        method: 'post',
        url: `index.php?option=com_seviersd&format=json&task=chromebook.getabcal&${this.mytoken}`,
        data: formData,
      })
          .then(response => {
            this.results = response.data;
            console.log(response.data);
            this.showSpinnerFlag = false;
            if (response.data.error) {
              this.showMessage = response.data.error;
              this.showMessageFlag = true
            } else {
              this.showMessageFlag = false
            }
          })
          .catch(function (error) {
            console.log(error);
          })

    },
    async saveAB() {
      this.disabled = true;
      this.showSpinnerFlag = true;
      const formData = new FormData();
      //if array you must use JSON.stringify
      formData.append('abstuff', JSON.stringify(this.results));
      await axios({
        method: 'post',
        url: `index.php?option=com_seviersd&format=json&task=chromebook.saveabcal&${this.mytoken}`,
        data: formData,
      })
          .then(response => {
            this.results = response.data;
            console.log(response.data);
            this.disabled = false;
            this.showSpinnerFlag = false;
            if (response.data.error) {
              this.showMessage = response.data.error;
              this.showMessageFlag = true
            } else {
              this.showMessageFlag = false
            }
          })
          .catch(function (error) {
            console.log(error);
          })

    },
  },
});

app.mount('#abcalendar');
</script>
