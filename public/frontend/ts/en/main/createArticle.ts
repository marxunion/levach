import 'prismjs/themes/prism.css';
import '@toast-ui/editor-plugin-code-syntax-highlight/dist/toastui-editor-plugin-code-syntax-highlight.css';
import 'tui-color-picker/dist/tui-color-picker.css';
import '@toast-ui/editor-plugin-color-syntax/dist/toastui-editor-plugin-color-syntax.css';

import Editor from '@toast-ui/editor';
import colorSyntax from '@toast-ui/editor-plugin-color-syntax';
import codeSyntaxHighlight from '@toast-ui/editor-plugin-code-syntax-highlight';


const editor = new Editor({
  plugins: [colorSyntax, codeSyntaxHighlight],
  el: document.querySelector('#editor') as HTMLElement,
  initialEditType: 'markdown',
  previewStyle: 'vertical'
});

editor.getMarkdown();