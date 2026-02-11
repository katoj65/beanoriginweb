<template>
<el-table :data="tableData" style="width: 100%;height:300px;">
<el-table-column prop="fname" label="Names" />
<el-table-column prop="lname" label=""/>
<el-table-column prop="farm" label="Farm" />
<el-table-column prop="produce" label="Produce" />
<el-table-column prop="location" label="Location" />
</el-table>
</template>

<script setup>
const tableData = [
{
fname:'Joshua',lname:'Kato',farm:'Wakiso Farm',produce:'Coffee',location:'Wakiso district'
},
{
fname:'Joshua',lname:'Kato',farm:'Wakiso Farm',produce:'Coffee',location:'Wakiso district'
},
{
fname:'Joshua',lname:'Kato',farm:'Wakiso Farm',produce:'Coffee',location:'Wakiso district'
},
{
fname:'Joshua',lname:'Kato',farm:'Wakiso Farm',produce:'Coffee',location:'Wakiso district'
},
{
fname:'Joshua',lname:'Kato',farm:'Wakiso Farm',produce:'Coffee',location:'Wakiso district'
},
{
fname:'Joshua',lname:'Kato',farm:'Wakiso Farm',produce:'Coffee',location:'Wakiso district'
},
{
fname:'Joshua',lname:'Kato',farm:'Wakiso Farm',produce:'Coffee',location:'Wakiso district'
},
{
fname:'Joshua',lname:'Kato',farm:'Wakiso Farm',produce:'Coffee',location:'Wakiso district'
},

]
</script>
