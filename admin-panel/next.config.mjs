/** @type {import('next').NextConfig} */
const nextConfig = {
  eslint: {
    ignoreDuringBuilds: true,
  },
  typescript: {
    ignoreBuildErrors: true,
  },
  images: {
    unoptimized: true,
  },
  async rewrites() {
    return [
      {
        source: '/api-proxy/:path*',
        // Your Laravel application URL
        destination: `${process.env.NEXT_PUBLIC_LARAVEL_PROXY_DESTINATION || 'http://bergambar.test'}/:path*`,
      },
    ]
  },
}

export default nextConfig
