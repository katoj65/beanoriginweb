<template>
<el-table :data="tableData" style="width: 100%;height:400px;" >
<el-table-column prop="batch" label="Batch ID"/>
<el-table-column prop="farm" label="Farm Name"/>
<el-table-column prop="variety" label="Coffee" />
<el-table-column prop="process" label="Processing" />
<el-table-column prop="grade" label="Quality Grade"/>
<el-table-column prop="date" label="Harvest Date" />
</el-table>
</template>

<script lang="ts" setup>
const tableData = [
{
batch: 'KB-2024-001',
farm: 'Kireka Hills Farm',
variety: 'SL14 Arabica',
process:'Fully Washed',
grade:'A+ Premium',
date:'15/01/2024'
},
{
batch: 'MW-2024-002',
farm: 'Mount Wamala Estate',
variety: 'Blue Mountain',
process:'Honey Process',
grade:'AA Superior',
date:'22/01/2024'
},
{
batch: 'BG-2024-003',
farm: 'Bugisu Highlands',
variety: 'SL28 Arabica',
process:'Natural Dry',
grade:'A Grade',
date:'28/01/2024'
},
{
batch: 'NK-2024-004',
farm: 'Nakaseke Plantation',
variety: 'Cattura',
process:'Semi-Washed',
grade:'B+ Good',
date:'05/02/2024'
},
{
batch: 'LW-2024-005',
farm: 'Lake Victoria Shores',
variety: 'Bourbon Arabica',
process:'Fully Washed',
grade:'AA Premium',
date:'12/02/2024'
},
{
batch: 'KS-2024-006',
farm: 'Kasese Mountain Farm',
variety: 'Geisha Variety',
process:'Black Honey',
grade:'AAA Specialty',
date:'18/02/2024'
},
{
batch: 'MB-2024-007',
farm: 'Mbale Cooperative',
variety: 'SL14 Arabica',
process:'Pulped Natural',
grade:'A+ Premium',
date:'25/02/2024'
},
{
batch: 'JN-2024-008',
farm: 'Jinja Valley Farm',
variety: 'Villa Sarchi',
process:'Washed Process',
grade:'A Grade',
date:'03/03/2024'
},
{
batch: 'KB-2024-009',
farm: 'Kabale Heights',
variety: 'Red Bourbon',
process:'Natural Process',
grade:'B+ Good',
date:'10/03/2024'
},
{
batch: 'MW-2024-010',
farm: 'Mubende Slopes',
variety: 'Pacamara',
process:'White Honey',
grade:'AA Superior',
date:'17/03/2024'
}
]
</script>
