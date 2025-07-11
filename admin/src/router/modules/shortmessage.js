import layoutHeaderAside from '@/layout/header-aside'

// 由于懒加载页面太多的话会造成webpack热更新太慢，所以开发环境不使用懒加载，只有生产环境使用懒加载
const _import = require('@/libs/util.import.' + process.env.NODE_ENV)

const meta = { auth: true }

export default {
  path: '/yun/shortmessage',
  name: 'yun-shortmessage',
  meta,
  redirect: { name: 'yun-shortmessage-index' },
  component: layoutHeaderAside,
  children: (pre => [
    { path: 'answer', name: `${pre}answer`, component: _import('yun/shortmessage/count/answer.vue'), meta: { ...meta, title: '答题' } }
  ])('yun-shortmessage-')
}
