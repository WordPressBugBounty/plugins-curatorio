(function (blocks, element, blockEditor, components) {
    var el = element.createElement;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var TextControl = components.TextControl;

    blocks.registerBlockType('curator/feed', {
        title: 'Curator Feed Embed',
        description: 'Display a Curator.io social media feed.',
        icon: 'share',
        category: 'embed',
        attributes: {
            feed_public_key: {
                type: 'string',
                default: '',
            },
        },

        edit: function (props) {
            var feedPublicKey = props.attributes.feed_public_key;

            return el(
                element.Fragment,
                null,
                el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        { title: 'Feed Settings', initialOpen: true },
                        el(TextControl, {
                            label: 'Feed Public Key',
                            help: 'Leave empty to use the default feed from Curator plugin settings.',
                            value: feedPublicKey,
                            onChange: function (value) {
                                props.setAttributes({ feed_public_key: value });
                            },
                        })
                    )
                ),
                el(
                    'div',
                    { className: 'curator-block-preview', style: {
                        padding: '20px',
                        backgroundColor: '#f0f0f0',
                        border: '1px solid #ddd',
                        borderRadius: '4px',
                        textAlign: 'center',
                    }},
                    el('img', {
                        src: curatorBlock.logoUrl,
                        alt: 'Curator.io',
                        style: { width: '120px', marginBottom: '10px' },
                    }),
                    el(TextControl, {
                        label: 'Feed Public Key',
                        placeholder: 'Leave empty to use the default feed public key from settings',
                        value: feedPublicKey,
                        onChange: function (value) {
                            props.setAttributes({ feed_public_key: value });
                        },
                        style: { maxWidth: '400px', margin: '0 auto' },
                    })
                )
            );
        },

        save: function () {
            return null;
        },
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor,
    window.wp.components
);
