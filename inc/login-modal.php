<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Welcome Back</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="overflow: visible;">
        <form method="POST" action="app/login.php">
               <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?=htmlspecialchars($_GET['error'])?>
                    </div>
               <?php } ?>
               <?php csrf_token(); ?>
               
               <div class="input-holder mb-3">
                   <label>Select Role</label>
                   <div class="assign-container" style="position: relative; z-index: 1050;">
                       <div class="selected-pills" id="rolePills">
                           <span class="placeholder-text" style="color: #94a3b8; font-size: 14px;">Select role...</span>
                       </div>
                       <button type="button" class="add-btn" id="roleBtn">
                           <i class="fa fa-chevron-down"></i>
                       </button>
                       
                       <div class="user-dropdown" id="roleDropdown" style="z-index: 1060;">
                           <label class="dropdown-item" style="display: flex; align-items: center; justify-content: space-between;">
                               <div style="display: flex; align-items: center; gap: 10px;">
                                   <div class="avatar-small" style="width: 30px; height: 30px; background: #f0fdf4; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">
                                       <i class="fa fa-user"></i>
                                   </div>
                                   <span>Club Member</span>
                               </div>
                               <input type="radio" name="role_radio" class="role-radio" value="club_member" data-name="Club Member" style="accent-color: var(--primary-color);">
                           </label>

                           <label class="dropdown-item" style="display: flex; align-items: center; justify-content: space-between;">
                               <div style="display: flex; align-items: center; gap: 10px;">
                                   <div class="avatar-small" style="width: 30px; height: 30px; background: #eff6ff; color: #1d4ed8; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">
                                       <i class="fa fa-user-secret"></i>
                                   </div>
                                   <span>Club Admin</span>
                               </div>
                               <input type="radio" name="role_radio" class="role-radio" value="club_admin" data-name="Club Admin" style="accent-color: var(--primary-color);">
                           </label>

                           <label class="dropdown-item" style="display: flex; align-items: center; justify-content: space-between;">
                               <div style="display: flex; align-items: center; gap: 10px;">
                                   <div class="avatar-small" style="width: 30px; height: 30px; background: #fff7ed; color: #ea580c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">
                                       <i class="fa fa-university"></i>
                                   </div>
                                   <span>Authority</span>
                               </div>
                               <input type="radio" name="role_radio" class="role-radio" value="authority" data-name="Authority" style="accent-color: var(--primary-color);">
                           </label>
                       </div>
                   </div>
                   <input type="hidden" name="role" id="roleInput" required>
               </div>

               <div class="form-floating mb-3">
                <input type="text" class="form-control" name="user_name" placeholder="Username" required>
                <label>Username</label>
              </div>
              <div class="form-floating mb-4">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <label>Password</label>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">LOGIN</button>
              </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function(){
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('action') === 'login'){
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }

        // Role Dropdown
        const roleBtn = document.getElementById('roleBtn');
        const roleDropdown = document.getElementById('roleDropdown');
        const rolePills = document.getElementById('rolePills');
        const roleRadios = document.querySelectorAll('.role-radio');
        const roleInput = document.getElementById('roleInput');

        if(roleBtn && roleDropdown) {
            const toggleRole = (e) => {
                e.stopPropagation();
                roleDropdown.classList.toggle('show');
            };

            roleBtn.addEventListener('click', toggleRole);
            rolePills.addEventListener('click', toggleRole);

            document.addEventListener('click', (e) => {
                if (!roleDropdown.contains(e.target) && !roleBtn.contains(e.target) && !rolePills.contains(e.target)) {
                    roleDropdown.classList.remove('show');
                }
            });

            roleRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    if(radio.checked) {
                        rolePills.innerHTML = `<div class="user-pill">${radio.dataset.name} <i class="fa fa-times" onclick="removeRole(event)"></i></div>`;
                        roleInput.value = radio.value;
                        roleDropdown.classList.remove('show');
                    }
                });
            });

            window.removeRole = function(e) {
                e.stopPropagation();
                rolePills.innerHTML = '<span class="placeholder-text" style="color: #94a3b8; font-size: 14px;">Select role...</span>';
                roleInput.value = '';
                roleRadios.forEach(r => r.checked = false);
            };
        }
    });
</script>
