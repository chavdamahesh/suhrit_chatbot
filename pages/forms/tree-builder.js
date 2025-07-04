// --- Tree Data Adapter: convert backend format to frontend format for rendering ---
function adaptTreeForRender(treeArr) {
    if (!Array.isArray(treeArr)) return [];
    return treeArr.map(node => {
        if (node.option) {
            return {
                type: 'option',
                label: node.option,
                children: adaptTreeForRender(node.children || [])
            };
        } else if (node.description) {
            return {
                type: 'desc',
                label: node.description
            };
        }
        return null;
    }).filter(Boolean);
}

// --- Build a tree node (option or description) ---
function buildNode(node, parentLabel = '') {
    const wrapper = $('<div class="ml-3 mb-3 d-flex align-items-start"></div>');
    const contentWrapper = $('<div style="flex:1"></div>');
    const title = $('<label class="font-weight-bold d-block"></label>').text(node.label ? node.label.charAt(0).toUpperCase() + node.label.slice(1) : parentLabel);

    let input, button;
    const childContainer = $('<div class="child-container mt-2"></div>');

    // If it's a description node (leaf)
    if (node.type === 'desc') {
        input = $('<input type="text" class="form-control mt-2" placeholder="Enter Description">').val(node.label || '');
        button = $('<button type="button" class="btn btn-primary btn-sm ml-2 mt-4">Add</button>');
    } else {
        input = $('<select class="form-control dynamic-select mt-2" multiple></select>');
        // Restore children as options
        const optionLabels = (node.children || []).filter(c => c.type === 'option').map(c => c.label);
        optionLabels.forEach(opt => {
            input.append(new Option(opt, opt, true, true));
        });
        setTimeout(() => {
            input.select2({
                tags: true,
                width: '100%',
                placeholder: `Enter options for ${node.label || parentLabel}`
            });
            input.val(optionLabels).trigger('change');
        }, 0);
        button = $('<button type="button" class="btn btn-primary btn-sm ml-2 mt-4">Stop</button>');
    }

    // Render children (for edit mode)
    if (node.type === 'option' && node.children && node.children.length) {
        node.children.forEach(child => {
            childContainer.append(buildNode(child, node.label));
        });
    }

    // On select2 change, dynamically build children
    input.on('change', function () {
        if (node.type === 'desc') return;
        const selected = $(this).val() || [];
        childContainer.empty();
        selected.forEach(value => {
            // If this value exists in children, use it, else create empty option
            let subnode = { type: 'option', label: value, children: [] };
            if (node.children) {
                const match = node.children.find(c => c.label === value && c.type === 'option');
                if (match) subnode = match;
            }
            childContainer.append(buildNode(subnode, node.label));
        });
    });

    // Add/Stop toggle logic
    button.on('click', function () {
        if (button.text() === "Stop") {
            input.select2 && input.select2('destroy');
            const textBox = $('<input type="text" class="form-control mt-2" placeholder="Enter Description">');
            input.replaceWith(textBox);
            input = textBox;
            button.text("Add");
            childContainer.empty();
        } else {
            // Replace with select2
            const newSelect = $('<select class="form-control dynamic-select mt-2" multiple></select>');
            setTimeout(() => {
                newSelect.select2({
                    tags: true,
                    width: '100%',
                    placeholder: `Enter options for ${node.label || parentLabel}`
                });
            }, 0);
            input.replaceWith(newSelect);
            input = newSelect;
            button.text("Stop");
            // On new select2 change
            newSelect.on('change', function () {
                const selected = $(this).val() || [];
                childContainer.empty();
                selected.forEach(value => {
                    childContainer.append(buildNode({ type: 'option', label: value, children: [] }, node.label));
                });
            });
        }
    });

    contentWrapper.append(title, input, childContainer);
    wrapper.append(contentWrapper, button);
    return wrapper;
}

// --- Render tree from backend JSON for edit ---
function renderTreeFromData(treeData, selector = '#selected-options') {
    $(selector).empty();
    const nodes = adaptTreeForRender(treeData);
    // For root, show all root options in a select2 at the top
    const rootLabels = nodes.filter(n => n.type === 'option').map(n => n.label);
    $('#tags').html('').off('change');
    rootLabels.forEach(lbl => {
        $('#tags').append(new Option(lbl, lbl, true, true));
    });
    setTimeout(() => {
        $('#tags').select2({
            tags: true,
            width: '100%',
            placeholder: "Enter root options"
        });
        $('#tags').val(rootLabels).trigger('change');
    }, 0);

    function rootRender() {
        $(selector).empty();
        const selected = $('#tags').val() || [];
        selected.forEach(rootOpt => {
            // Find children for this root
            let node = nodes.find(n => n.label === rootOpt && n.type === 'option');
            if (!node) node = { type: 'option', label: rootOpt, children: [] };
            $(selector).append(buildNode(node, ''));
        });
    }

    $('#tags').on('change', rootRender);
    rootRender();
}

// --- Collect tree data for saving back to backend format ---
function collectTreeData(container) {
    const data = [];
    container.children('.d-flex').each(function () {
        const label = $(this).find('label').first().text().trim();
        const inputField = $(this).find('select, input').first();
        const childContainer = $(this).find('.child-container').first();

        if (inputField.is('input')) {
            const description = inputField.val();
            if (description) {
                data.push({
                    description: description,
                    children: []
                });
            }
        } else if (inputField.is('select')) {
            const selectedOptions = inputField.val() || [];
            selectedOptions.forEach(optionValue => {
                const children = collectTreeData(childContainer);
                data.push({
                    option: optionValue,
                    children: children
                });
            });
        }
    });
    return data;
}