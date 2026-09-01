        var extraFieldCount = 0;
        function addExtraField() {
            if (extraFieldCount >= 2) {
                alert("Maximum 2 custom dynamic fields allowed.");
                return;
            }
            extraFieldCount++;
            var container = document.getElementById('extra-fields-container');
            var fieldGroup = document.createElement('div');
            fieldGroup.className = 'form-group extra-group';
            fieldGroup.innerHTML = `
                <div class="extra-row">
                    <input type="text" name="extra_label_${extraFieldCount}" placeholder="Field Label (e.g. Priority)" class="form-control">
                    <input type="text" name="extra_value_${extraFieldCount}" placeholder="Field Value (e.g. High)" class="form-control">
                </div>
            `;
            container.appendChild(fieldGroup);
            if (extraFieldCount === 2) {
                document.getElementById('add-field-btn').style.display = 'none';
            }
        }