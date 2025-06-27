export default {
  path: '/yun/shortmessage',
  title: '后台管理',
  icon: 'globe',
  children: (pre => [
    { path: `${pre}answer`, title: '答题', icon: 'bar-chart' }
  ])('/yun/shortmessage/')
}
