<script setup>
import { computed } from 'vue';
import { useForm,usePage } from '@inertiajs/vue3';


const props=defineProps({
user: Object,
profile:Object
});



const user=computed(()=>{
return props.user;
});

const profile=computed(()=>{
return props.profile;
});

const page=usePage();



const getInitials = (name) => {
if (!name) return 'NA';
return name
.split(' ')
.map((part) => part[0])
.join('')
.slice(0, 2)
.toUpperCase();
};



const form=useForm({
item:''
});


const submit=(item)=>{
form.item=item;
form.post(route('update_user_account_status'));
}

const roles=computed(()=>page.props.response.user_roles);
const roleCount = computed(() => (Array.isArray(roles.value) ? roles.value.length : 0));

const capitalizeFirst = (value) => {
  const text = String(value ?? '');
  if (!text) return '';
  return text.charAt(0).toUpperCase() + text.slice(1);
};

const roleIcon = (role, icon) => {
  if (icon) return icon;

  const key = String(role ?? '').toLowerCase();
  if (key.includes('cooperative')) return 'ni-users-fill';
  if (key.includes('farmer')) return 'ni-growth-fill';
  if (key.includes('export')) return 'ni-truck';
  if (key.includes('buyer')) return 'ni-bag-fill';
  if (key.includes('financ')) return 'ni-wallet-fill';
  if (key.includes('admin')) return 'ni-shield-check-fill';
  if (key.includes('business')) return 'ni-briefcase-fill';
  return 'ni-user-fill';
};

















</script>
<template>
<div class="modal-content responsive-modal-content select-business-shell">
<div class="modal-body p-0 responsive-modal-body">
<div class="row g-0 modal-split-row">
<div class="col-12 col-md-4 pt-3 modal-left-pane">
<div class="profile-card">
<div class="user-card user-card-s2 profile-header">
<div class="user-avatar lg profile-avatar">
<span>{{ getInitials(user.fname+' '+user.lname) }}</span>
<div class="status dot dot-lg dot-success"></div>
</div>
<div class="user-info profile-user-info">
<span class="profile-label">User Account</span>
<h6 class="mb-1">{{ user.fname+' '+user.lname }}</h6>
<span class="sub-text profile-email">{{ user.email }}</span>
</div>
</div>

<ul class="team-info profile-meta">
<li><span>Gender</span><span>{{ profile.gender || 'N/A' }}</span></li>
<li><span>DOB</span><span>{{ profile.dob || 'N/A' }}</span></li>
<li><span>Telephone Number</span><span>{{ profile.tel || 'N/A' }}</span></li>
<li><span>Role</span><span>{{ capitalizeFirst(user.role) || 'N/A' }}</span></li>
</ul>
</div>

</div>






<div class="col-12 col-md-8 modal-right-pane">
<div class="card h-100 border-0 rounded-0 services-panel">
<div class="card-inner p-3 p-md-4">

<div class="card-title-group mb-1 modern-title-group">
<div class="card-title service-heading">
<span class="service-kicker text-black">Business Setup</span>
<h5 class="title mb-1">
Choose Your Primary Service
</h5>
<p class="sub-text mb-0">This sets your dashboard defaults and recommended actions. You can change it later.</p>
</div>
<span class="roles-count">{{ roleCount }} roles</span>
</div>



<div class="card-body service-body">




<div class="service-grid">
<button
v-for="(l,key) in roles"
:key="key"
type="button"
class="service-tile"
@click="submit(l.role)"
>
<div class="service-tile-top">
<div class="service-icon-wrap">
<em :class="`icon ni ${roleIcon(l.role, l.icon)}`"></em>
</div>
<em class="icon ni ni-arrow-right service-arrow"></em>
</div>
<h6 class="service-name mb-1">{{ capitalizeFirst(l.role) }}</h6>
<p class="service-description mb-0">{{ l.description || 'No description available yet.' }}</p>
</button>
</div>




</div>


</div>
</div>







</div>
</div>











</div>
</div>
</template>

<style scoped>
.select-business-shell {
  border: 0;
  border-radius: 16px;
  overflow: hidden;
}

.modal-left-pane {
  padding-right: 0.4rem;
  background: linear-gradient(180deg, #f8fbff 0%, #f3f8ff 100%);
}

.profile-card {
  background: transparent;
  border: 0;
  border-radius: 14px;
  padding: 0.95rem;
  box-shadow: none;
}

.profile-header {
  align-items: center;
  gap: 0.8rem;
  padding-bottom: 0.75rem;
  margin-bottom: 0.4rem;
  border: 0;
  background: transparent;
  box-shadow: none;
}

.profile-avatar {
  background: linear-gradient(135deg, #1d4ed8 0%, #0ea5e9 100%);
  color: #ffffff;
  box-shadow: 0 8px 16px rgba(14, 116, 215, 0.25);
}

.profile-avatar .status {
  border: 2px solid #ffffff;
}

.profile-user-info h6 {
  color: #0f172a;
  font-weight: 700;
}

.profile-label {
  display: inline-block;
  font-size: 0.68rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: #1d4ed8;
  font-weight: 700;
  margin-bottom: 0.2rem;
}

.profile-email {
  color: #475569;
  font-size: 0.78rem;
}

.profile-meta {
  list-style: none;
  padding: 0;
  margin: 0;
}

.profile-meta li {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 0.65rem;
  padding: 0.55rem 0;
  border-bottom: 1px solid #edf2f8;
  font-size: 0.82rem;
}

.profile-meta li:last-child {
  border-bottom: 0;
  padding-bottom: 0.15rem;
}

.profile-meta li span:first-child {
  color: #64748b;
  font-weight: 600;
}

.profile-meta li span:last-child {
  color: #0f172a;
  font-weight: 600;
  text-align: right;
  overflow-wrap: anywhere;
}

.modal-right-pane {
  background: #ffffff;
}

.services-panel {
  background: linear-gradient(180deg, #ffffff 0%, #fcfdff 100%);
}

.modern-title-group {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 0.75rem;
}

.service-heading {
  display: flex;
  flex-direction: column;
  gap: 0.2rem;
}

.service-kicker {
  display: inline-block;
  font-size: 0.7rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: #1d4ed8;
}

.service-heading .title {
  color: #0f172a;
  font-weight: 800;
  font-size: 1.5rem;
  line-height: 1.2;
}

.service-heading .sub-text {
  color: #475569;
  max-width: 52ch;
}

.roles-count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.3rem 0.6rem;
  border-radius: 999px;
  border: 1px solid #d7e4fb;
  background: #f3f7ff;
  color: #1d4ed8;
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  line-height: 1;
}

.service-body {
  padding-top: 0.6rem;
}

.service-grid {
  display: grid;
  gap: 0.65rem;
  grid-template-columns: repeat(3, minmax(0, 1fr));
}

.service-tile {
  text-align: left;
  display: flex;
  flex-direction: column;
  align-items: stretch;
  justify-content: flex-start;
  width: 100%;
  border: 1px solid #dde6f3;
  border-radius: 12px;
  background: linear-gradient(145deg, #ffffff 0%, #f7fafc 100%);
  padding: 0.75rem;
  min-height: 130px;
  transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}

.service-tile:hover {
  transform: translateY(-2px);
  border-color: #7aa2f7;
  box-shadow: 0 12px 24px rgba(15, 23, 42, 0.1);
}

.service-tile:focus-visible {
  outline: 0;
  border-color: #1d4ed8;
  box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.15);
}

.service-tile-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 0.5rem;
}

.service-icon-wrap {
  width: 36px;
  height: 36px;
  border-radius: 9px;
  background: #eaf2ff;
  color: #1f4fa3;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.95rem;
}

.service-name {
  color: #0f172a;
  font-weight: 700;
  font-size: 0.92rem;
  line-height: 1.2;
}

.service-description {
  color: #475569;
  font-size: 0.78rem;
  line-height: 1.35;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.service-arrow {
  color: #5b6b80;
  font-size: 0.9rem;
}

@media (max-width: 992px) {
  .service-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 768px) {
  .modal-left-pane {
    padding-right: 0;
    margin-bottom: 0.6rem;
  }

  .modern-title-group {
    flex-direction: column;
  }

  .service-grid {
    grid-template-columns: 1fr;
  }
}
</style>
