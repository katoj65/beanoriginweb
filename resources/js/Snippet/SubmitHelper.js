import { ElMessage } from 'element-plus'



const submitHelpRequest = () => {
form.post(route('cooperative.help.store'), {
onSuccess: () => {
ElMessage({
message: page.props.flash?.success,
type: 'success',
placement: 'bottom-left',
customClass: 'el-success-message',
});
form.reset();
},
});
}
